<?php
$GLOBALS['con'] = mysqli_connect('localhost', 'root', 'root')
or die('数据库连接失败');
mysqli_select_db($GLOBALS['con'], 'mydb') or die('选择数据库失败');
mysqli_set_charset($GLOBALS['con'], 'utf8');
?>


