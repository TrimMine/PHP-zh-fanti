# PHP-zh-fanti
简体转繁体  原文https://github.com/NauxLiu/opencc4php 


  转载自 https://github.com/NauxLiu/opencc4php  中文简体转繁体文章 此处仅仅是写一些安装中遇到的问题

  opencc4php 是OpenCC的PHP扩展，能很智能的完成简繁体转换。 
  需要先安装OpenCC扩展 如果此处安装失败可去管方githup地址重新下载编译安装
 
  你需要先安装1.0.1 版本以上的OpenCC，
  

  安装OpenCC：
  
  git clone https://github.com/BYVoid/OpenCC.git --depth 1
  cd OpenCC
  make
  sudo make install


  安装opencc4php：

  git clone git@github.com:NauxLiu/opencc4php.git --depth 1
  cd opencc4php
  phpize    
  ./configure
  make && sudo make install
  
  如果你的OpenCC安装目录不在/usr或/usr/local，可在./configure时添加--with-opencc=[DIR]指定你的OpenCC目录

  要注意phpzie的php版本  多个版本要指定 ./configure --with-php-config=/www/server/php/bin/php-config

  安装完成后加入到php.ini文件最后一行加入

  /www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/ 这个路径安装完成会显示
  extension =  /www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/opencc.so

  如果php -m 提示这条错误
  PHP Startup: Unable to load dynamic library '/www/server/php/71/lib/php/extensions/no-debug-non-zts-20160303/opencc.so' - libopencc.so.2: cannot open shared object file: No such file or directory in Unknown on line 0

  那么需要执行 ln -s /usr/lib/libopencc.so.2 /usr/lib64/libopencc.so.2
  
  最后查看 php -m 是否有opencc  如果有则重启php开始使用 
  
  
  #原文章作者给出的列子
  $od = opencc_open("s2twp.json"); //传入配置文件名
  $text = opencc_convert("我鼠标哪儿去了。", $od);
  echo $text;
  opencc_close($od);
