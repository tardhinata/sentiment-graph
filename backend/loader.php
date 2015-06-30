<?php
include 'py_config.php'; 
//$res = shell_exec("$python loader.py --filetodb data/citations_16_00_04_04_14.net");
$res = shell_exec("$python loader.py -p isern_citations");

echo $res;
?>