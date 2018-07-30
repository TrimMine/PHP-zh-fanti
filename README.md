# PHP-zh-fanti
简体转繁体  原文https://github.com/NauxLiu/opencc4php 


  转载自 https://github.com/NauxLiu/opencc4php  中文简体转繁体文章 此处仅仅是写一些安装中遇到的问题

  opencc4php 是OpenCC的PHP扩展，能很智能的完成简繁体转换。 
  需要先安装OpenCC扩展 如果此处安装失败可去管方githup地址重新下载编译安装

  要注意phpzie的php版本  多个版本要指定 ./configure --with-php-config=/www/server/php/bin/php-config

  安装完成后加入到php.ini文件最后一行加入

  /www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/ 这个路径安装完成会显示
  extension =  /www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/opencc.so

  如果php -m 提示这条错误
  PHP Startup: Unable to load dynamic library '/www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/opencc.so' - libopencc.so.2: cannot open shared object file: No such file or directory in Unknown on line 0

  那么需要执行 ln -s /usr/lib/libopencc.so.2 /usr/lib64/libopencc.so.2
  
  最后查看 php -m 是否有opencc  如果有则重启php开始使用 
  
  
  
  translate 
  此文件夹是翻译的文件 此处简体转繁体主要是lang文件中的fanTi方法,调用的上面安装的扩展方法
  
  Lang.php 
  翻译处理的文件
  
  Translate 
  中文转英语的翻译
  
  
  common.php
  此处是TP5的公共类文件,适用于返回json数据类型的信息做出处理 目前支持 中文转英文,简体中文转繁体

