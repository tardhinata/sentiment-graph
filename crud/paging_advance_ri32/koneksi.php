<?php
$host="localhost";
$user="root";
$pass="";
$db="db_ri32";
$paging=10;

$koneksi=mysql_connect($host,$user,$pass);
mysql_select_db($db,$koneksi);
?>