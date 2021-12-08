# Goland 配置远程 FTP Server

[TOC]

## Centos 安装配置 FTP

### 安装 VSFTPD

1.更新

```shell
sudo yum update
```

2.使用 yum 安装 vsftpd

```shell
sudo yum install vsftpd
```

3.开启服务并设置开机启动

```shell
sudo systemctl start vsftpd
```

```shell
sudo systemctl enable vsftpd
```

4.开启防火墙

```shell
sudo firewall-cmd --zone=public --permanent --add-port=21/tcp
```

```shell
sudo firewall-cmd --zone=public --permanent --add-service=ftp
```

```shell
sudo firewall-cmd --reload
```

### 设置 VSFTPD

1.备份 vsftpd 配置文件

```shell
sudo cp /etc/vsftpd/vsftpd.conf /etc/vsftpd/vsftpd.conf.default
```

2.修改配置文件

```shell
sudo vim /etc/vsftpd/vsftpd.conf
```

3.禁止匿名用户登录、允许本地用户登录

```shell
anonymous_enable=NO

local_enable=YES
```

4.允许用户上传文件

```shell
write_enable=YES
```

5.限定上传目录

```shell
chroot_local_user=YES
```