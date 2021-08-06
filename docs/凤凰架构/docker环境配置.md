# 加速器
加速器地址

```
https://01xu5ska.mirror.aliyuncs.com
```

# 操作文档

## Mac

* 安装／升级Docker客户端
    * 对于10.10.3以下的用户 推荐使用 [Docker Toolbox](http://mirrors.aliyun.com/docker-toolbox/mac/docker-toolbox/)

    * 对于10.10.3以上的用户 推荐使用 [Docker for Mac](http://mirrors.aliyun.com/docker-toolbox/mac/docker-for-mac/)


* 配置镜像加速器
    * Docker Toolbox
    * Docker for Mac 
        * ![](https://hugopost.oss-cn-shanghai.aliyuncs.com/89718277-E75B-4D40-AE14-C936A19DEC49.png)
        * Apply & Restart


* 相关文档

[Docker 命令参考文档](https://docs.docker.com/engine/reference/commandline/cli/)

[Dockerfile 镜像构建参考文档](https://docs.docker.com/engine/reference/builder/)

## Ubuntu

## CentOS

* 安装／升级Docker客户端

推荐安装1.10.0以上版本的Docker客户端，参考文档 [docker-ce](https://developer.aliyun.com/article/110806)



* 配置镜像加速器

    * 针对Docker客户端版本大于 1.10.0 的用户您可以通过修改daemon配置文件/etc/docker/daemon.json来使用加速器

```
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://01xu5ska.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
```