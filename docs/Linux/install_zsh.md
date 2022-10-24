# 使用Gitee镜像安装和配置 oh-my-zsh

[TOC]

## 已安装

### 更改镜像

```bash
cd ~/.oh-my-zsh
git remote set-url origin https://gitee.com/mirrors/oh-my-zsh.git
```

### 查看镜像

```bash
git remote -v
```

## 未安装

### 下载安装文件

```bash
wget https://gitee.com/mirrors/oh-my-zsh/raw/master/tools/install.sh
```

### 更换镜像地址

```bash
sed -i 's/REPO=${REPO:-ohmyzsh\/ohmyzsh}/REPO=${REPO:-mirrors\/oh-my-zsh}/' install.sh
sed -i 's/REMOTE=${REMOTE:-https:\/\/github.com\/${REPO}.git}/REMOTE=${REMOTE:-https:\/\/gitee.com\/${REPO}.git}/' install.sh
```

### 添加执行权限

```bash
chmod +x install.sh
```

### 执行安装

```bash
./install.sh
```

## 配置
