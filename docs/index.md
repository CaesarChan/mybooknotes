# NahcRaseac's NoteBook

## mkdocs & markdown

* [mkdocs](https://github.com/mkdocs/mkdocs)
* [markdown](http://xianbai.me/learn-md/article/about/readme.html)

* 安装依赖：

```sh
# 方式1：
pip install mkdocs    # 制作电子书, http://markdown-docs-zh.readthedocs.io/zh_CN/latest/
# https://stackoverflow.com/questions/27882261/mkdocs-and-mathjax/31874157
pip install https://github.com/mitya57/python-markdown-math/archive/master.zip

# 推荐方式2：
pip install -r requirements.txt
```

* 编写并查看：

```sh
mkdocs serve     # 修改自动更新，浏览器打开 http://localhost:8000 访问
# 数学公式参考 https://www.zybuluo.com/codeep/note/163962
mkdocs gh-deploy    # 部署到自己的 github pages, 如果是 readthedocs 会自动触发构建
```

## 优化访问 - 去除 Google 字体

* 自定义 **theme** 并创建 **main.html**

```bash
mkdir custom_theme
touch main.html
```

* 模板内容替换

```html
{% extends "base.html" %}

{% block htmltitle %}
<title>CaesarChan的读书笔记</title>
{% endblock %}

{%- block styles %}
<link rel="stylesheet" href="{{ 'css/theme.css'|url }}" />
<link rel="stylesheet" href="{{ 'css/theme_extra.css'|url }}" />
{%- if config.theme.highlightjs %}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/styles/github.min.css" />
{%- endif %}
{%- for path in extra_css %}
<link href="{{ path }}" rel="stylesheet" />
{%- endfor %}
{%- endblock %}

```

## 发布到 readthedoc

1. 使用 GitHub 账号注册并导入项目
2. 登入 dashboard，选择管理>>设置>>高级设置，选择 Python版本 2.7
3. 手动构建并访问

## 使用 OSS 保存图片

### MacOS 安装 ossutil

* 下载ossutil

```bash
curl -o ossutilmac64 http://gosspublic.alicdn.com/ossutil/1.7.5/ossutilmac64
```

* 修改文件执行权限

```bash
chmod 755 ossutilmac64
mv ossutilmac64 /usr/local/bin 
source ~/.zshrc
```

* 生成配置文件

```bash
ossutilmac64 config
```

* 上传文件

```bash
ossutilmac64 cp {{your_file}} -r oss://{{your_bucket}}
```

## 使用 PlantUML 绘制图片

* VsCode 安装 PlantUML 插件 并启用

* 绘制脑图  

```xml
@startmindmap
'https://plantuml.com/mindmap-diagram

caption 简介
title 简介 

* 读书笔记 
** 1 本电子书制作和写作方式
*** mkdocs
*** markdown


** 2 优化访问 - 去除 Google 字体
*** 自定义 theme
*** 修改 base.html 


** 3 发布到 readthedoc
*** 使用 GitHub 账号注册并导入项目
*** 登入 dashboard，选择管理>>设置>>高级设置，选择 Python版本 2.7
*** 手动构建并访问

** 4 使用 OSS 保存图片
*** MacOS 安装 ossutil
*** 配置文件


** 5 使用 PlantUML 绘制图片
*** VsCode 安装 PlantUML
*** 新增 index.puml 文件
*** 保存图片并上传到 OSS


@endmindmap

```

![index xmind](https://hugopost.oss-cn-shanghai.aliyuncs.com/index/%E7%AE%80%E4%BB%8B.png)
