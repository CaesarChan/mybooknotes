# 安装集群

[TOC]

## 1 使用 Kubeaz 快速安装集群

## 2 购买域名

## 3 CES 配置负载均衡

## 4 搭建 NFS 作为默认 SC

### 4.1 配置 NFS-Server

安装客户端工具

```shell
yum install -y nfs-utils
```

执行命令 vi /etc/exports，创建 exports 文件，文件内容如下：

```shell
echo "/nfs/data/ *(insecure,rw,sync,no_root_squash)" > /etc/exports
```

执行以下命令，启动 nfs 服务

创建共享目录

```shell
mkdir -p /nfs/data
systemctl enable rpcbind
systemctl enable nfs-server
systemctl start rpcbind
systemctl start nfs-server
exportfs -r
```

检查配置是否生效

```shell
exportfs
```

输出结果如下所示
```/nfs/data /nfs/data```

测试Pod直接挂载NFS了

注意数据替换：**server: {nfs 服务器IP}**

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: vol-nfs
  namespace: default
spec:
  volumes:
  - name: html
    nfs:
      path: /nfs/data  
      server: 172.19.130.131 
  containers:
  - name: myapp
    image: nginx
    volumeMounts:
    - name: html
      mountPath: /usr/share/nginx/html/
```

### 4.2 搭建 NFS-Client

**服务器端防火墙开放111、662、875、892、2049的 tcp / udp 允许，否则远端客户无法连接。**

安装客户端工具

```shell
yum install -y nfs-utils
```

执行以下命令检查 nfs 服务器端是否有设置共享目录

```shell
#showmount -e $(nfs服务器的IP)
showmount -e 172.19.130.131 
```

执行以下命令挂载 nfs 服务器上的共享目录到本机路径 /root/nfsmount

```shell
mkdir /root/nfsmount
```

高可用备份的方式

```shell
# mount -t nfs $(nfs服务器的IP):/root/nfs_root /root/nfsmount
mount -t nfs 172.19.130.131:/nfs/data /root/nfsmount
```

写入一个测试文件

```shell
echo "hello nfs server" > /root/nfsmount/test.txt
```

在 nfs 服务器上执行以下命令，验证文件写入成功

```cat /root/nfsmount/test.txt```

### 4.3 设置动态供应

#### 4.3.1 创建provisioner

创建授权

```vi nfs-rbac.yaml```

```yaml
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: nfs-provisioner
---
kind: ClusterRole
apiVersion: rbac.authorization.k8s.io/v1
metadata:
   name: nfs-provisioner-runner
rules:
   -  apiGroups: [""]
      resources: ["persistentvolumes"]
      verbs: ["get", "list", "watch", "create", "delete"]
   -  apiGroups: [""]
      resources: ["persistentvolumeclaims"]
      verbs: ["get", "list", "watch", "update"]
   -  apiGroups: ["storage.k8s.io"]
      resources: ["storageclasses"]
      verbs: ["get", "list", "watch"]
   -  apiGroups: [""]
      resources: ["events"]
      verbs: ["watch", "create", "update", "patch"]
   -  apiGroups: [""]
      resources: ["services", "endpoints"]
      verbs: ["get","create","list", "watch","update"]
   -  apiGroups: ["extensions"]
      resources: ["podsecuritypolicies"]
      resourceNames: ["nfs-provisioner"]
      verbs: ["use"]
---
kind: ClusterRoleBinding
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: run-nfs-provisioner
subjects:
  - kind: ServiceAccount
    name: nfs-provisioner
    namespace: default
roleRef:
  kind: ClusterRole
  name: nfs-provisioner-runner
  apiGroup: rbac.authorization.k8s.io
```

创建nfs-client的授权
**这个镜像中volume的mountPath默认为/persistentvolumes，不能修改，否则运行时会报错**

```vi nfs-deployment.yaml```

```yaml
kind: Deployment
apiVersion: apps/v1
metadata:
   name: nfs-client-provisioner
spec:
   replicas: 1
   strategy:
     type: Recreate
   selector:
     matchLabels:
        app: nfs-client-provisioner
   template:
      metadata:
         labels:
            app: nfs-client-provisioner
      spec:
         serviceAccount: nfs-provisioner
         containers:
            -  name: nfs-client-provisioner
               image: lizhenliang/nfs-client-provisioner
               volumeMounts:
                 -  name: nfs-client-root
                    mountPath:  /persistentvolumes
               env:
                 -  name: PROVISIONER_NAME 
                    value: storage.pri/nfs 
                 -  name: NFS_SERVER
                    value: 172.19.130.131
                 -  name: NFS_PATH
                    value: /nfs/data
         volumes:
           - name: nfs-client-root
             nfs:
               server: 172.19.130.131
               path: /nfs/data
```

#### 4.3.2 创建 StorageClass

```vi storageclass-nfs.yaml```

```yaml
apiVersion: storage.k8s.io/v1
kind: StorageClass
metadata:
  name: storage-nfs
provisioner: storage.pri/nfs
reclaimPolicy: Delete
```

扩展"reclaim policy"有三种方式：**Retain**、**Recycle**、**Deleted**。

**Retain**
保护被PVC释放的PV及其上数据，并将PV状态改成"released"，不将被其它PVC绑定。集群管理员手动通过如下步骤释放存储资源：
手动删除PV，但与其相关的后端存储资源如(AWS EBS, GCE PD, Azure Disk, or Cinder volume)仍然存在。
手动清空后端存储volume上的数据。
手动删除后端存储volume，或者重复使用后端volume，为其创建新的PV。

**Delete**
删除被PVC释放的PV及其后端存储volume。对于动态PV其"reclaim policy"继承自其"storage class"，
默认是Delete。集群管理员负责将"storage class"的"reclaim policy"设置成用户期望的形式，否则需要用
户手动为创建后的动态PV编辑"reclaim policy"

**Recycle**
保留PV，但清空其上数据，已废弃

#### 改变默认 StorageClass

[官方文档](https://kubernetes.io/zh/docs/tasks/administer-cluster/change-default-storage-class/#%e4%b8%ba%e4%bb%80%e4%b9%88%e8%a6%81%e6%94%b9%e5%8f%98%e9%bb%98%e8%ae%a4-storage-class)

```shell

kubectl patch storageclass storage-nfs -p '{"metadata": {"annotations":{"storageclass.kubernetes.io/is-default-class":"true"}}}'

```

检查

```kubectl get sc```

### 4.4 验证 nfs 动态供应

#### 4.4.1  创建 pvc

```shell
vi  pvc.yaml
```

```yaml
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: pvc-claim-01
spec:
  storageClassName: storage-nfs  
  accessModes:
    - ReadWriteMany
  resources:
    requests:
      storage: 1Mi
```

#### 4.4.2 使用 pvc

```vi pvc_pod_test.yaml```

```yaml
kind: Pod
apiVersion: v1
metadata:
  name: test-pod
spec:
  containers:
  - name: test-pod
    image: busybox
    command:
      - "/bin/sh"
    args:
      - "-c"
      - "touch /mnt/SUCCESS && exit 0 || exit 1"
    volumeMounts:
      - name: nfs-pvc
        mountPath: "/mnt"
  restartPolicy: "Never"
  volumes:
    - name: nfs-pvc
      persistentVolumeClaim:
        claimName: pvc-claim-01
```

#### 4.4.2 NFS动态存储问题

[kubeasz 3.0.0 NFS动态存储问题](https://github.com/easzlab/kubeasz/issues/989)

## 5 快速安装 Kubesphere

[官方文档](https://kubesphere.io/docs/quick-start/minimal-kubesphere-on-k8s/)

## 参考文档

[一站式搭建](https://www.yuque.com/leifengyang/kubesphere/grw8se#TCVCL)