DoitPHP 1.5 (MySQL专业版)
=============================

感谢您选用doitphp, 这是一个简单易用,运行高效,易于扩展的轻量级PHP框架

DoitPHP MySQL专业版 与标准版最大的区别是：MySQL专业版支持MYSQL数据库的主从分离,标准版不支持。


安装
------------

1.将doitphp的压缩包解压后,在解压后的文件内你会看到以下文件和目录

      doitphp/		   框架的源文件
      tools/		   doitphp的辅助开发工具
      LICENSE              doitphp的许可证
      README               说明文件

      注:如需手册及演示实例, 请从官方网址另行下载。

	视频教程网址：http://bbs.doitphp.com/thread-495-1-1.html (供DoitPHP初学者使用)


2.运行doitphp的辅助开发工具(http://hostname/doitphpPath/tools/index.php),

	默认用户名:doitphp, 密码:123456 
	
	默认用户名密码更改文件./tools/config.ini.php

3.查看WebApp目录权限
	
	登陆tools之后-->WebApp管理-->查看WebApp目录权限-->点击‘查看WebApp目录权限’按钮

4.创建项目文件目录
	
	登陆tools之后-->WebApp管理-->创建WebApp目录-->点击‘创建Webapp目录’按钮
	
	注:操作到此,doitphp已经安装完成,接下来就是项目开发...



5.创建数据库连接配置文件(如果您开发的程序无需数据库操作,此步可省略)

	数据库连接配置文件config.ini.php在创建项目文件目录时,已创建完成，只需更改数据库连接参数即可。

6.创建controller文件
	
	登陆tools之后-->Controller管理-->创建Controller-->输入要创建的Controller文件名-->点击‘创建Controller’按钮


要求
------------

基本要求:web服务器运行的PHP版本5.1.0或以上,且支持gd扩展. 

如PHP运行环境不支持gd扩展,doitphp的辅助开发工具登陆时,无法显示验证码。
解决办法：将./tools/application/views/index/LoginController.class.php文件中#35至#38这三行代码注释掉就OK了。


tools配置文件
------
设置doitphp tools的配置文件./tools/config.ini.php
可以更改登陆用户名及密码，还可以更改WebApp目录。通过设置WebApp目录，可以实现管理多个项目。
注：默认参数已将doitphp和tools之间的调用设置好了，默认WebApp目录为doitphp所在目录。