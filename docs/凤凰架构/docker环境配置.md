# Docker 环境配置

## 加速器

* 加速器地址

    ```
    https://01xu5ska.mirror.aliyuncs.com
    ```

## 操作文档

## Mac

* 安装／升级Docker客户端
    * 对于10.10.3以下的用户 推荐使用 [Docker Toolbox](http://mirrors.aliyun.com/docker-toolbox/mac/docker-toolbox/)
    * 对于10.10.3以上的用户 推荐使用 [Docker for Mac](http://mirrors.aliyun.com/docker-toolbox/mac/docker-for-mac/)

* 配置镜像加速器
    * Docker for Mac
        * ![registry for Mac](https://hugopost.oss-cn-shanghai.aliyuncs.com/89718277-E75B-4D40-AE14-C936A19DEC49.png)
        * Apply & Restart

* 相关文档
    * [Docker 命令参考文档](https://docs.docker.com/engine/reference/commandline/cli/)
    * [Dockerfile 镜像构建参考文档](https://docs.docker.com/engine/reference/builder/)

## Linux

### [Centos 安装 Docker](https://docs.docker.com/engine/install/centos/)

#### 删除旧版本

```bash
sudo yum remove docker \
                  docker-client \
                  docker-client-latest \
                  docker-common \
                  docker-latest \
                  docker-latest-logrotate \
                  docker-logrotate \
                  docker-engine
```

#### 从 repositry 安装

##### 设置 repositry

```bash
sudo yum install -y yum-utils

sudo yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo
```

##### 安装 Docker Engine

1. 安装最新版本

    ```bash
    sudo yum install docker-ce docker-ce-cli containerd.io
    ```

2. 安装指定版本

    * 查看版本信息

        ```
        yum list docker-ce --showduplicates | sort -r
        ```

    * 安装示例: VERSION_STRING= **docker-ce-18.09.1**

        ```
        sudo yum install docker-ce-<VERSION_STRING> docker-ce-cli-<VERSION_STRING> containerd.io
        ```

3. 启动 Docker

    ```
    sudo systemctl start docker
    ```

### [Ubuntu 安装 Docker](https://docs.docker.com/engine/install/ubuntu/)

### 配置镜像加速器

针对Docker客户端版本大于 1.10.0 的用户您可以通过修改daemon配置文件/etc/docker/daemon.json来使用加速器

```bash
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://01xu5ska.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
```
