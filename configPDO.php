<?php
require 'medoo.php';

/*
// application config
$appaddress = 'http://10.10.168.19'; 

// mysql config
$dbtype = 'mysql';
$dbname = 'gerrit_ovirt';
$hostname = 'localhost';
$username = 'root';
$password = '';

// Database Connection using Medoo framework
try {
	$db = new medoo ( [
			// required
			'database_type' => $dbtype,
			'database_name' => $dbname,
			'server' => $hostname,
			'username' => $username,
			'password' => $password,
			// optional
			'port' => 3306,
			'charset' => 'utf8',
			// driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
			'option' => [
					PDO::ATTR_CASE => PDO::CASE_NATURAL
			]
	] );
} catch ( Exception $e ) {
	echo $e->getMessage ();
}
*/

// application config
$appaddress = 'http://sentimentgraph.ardhinata.com'; 

// mysql config
$dbtype = 'mysql';
$dbname = 'ardhdrqa_gerrit_ovirt';
$hostname = 'server146.web-hosting.com';
$username = 'ardhdrqa_thesis';
$password = 'CibulaThesis';

// Database Connection using Medoo framework
try {
	$db = new medoo ( [
			// required
			'database_type' => $dbtype,
			'database_name' => $dbname,
			'server' => $hostname,
			'username' => $username,
			'password' => $password,
			// optional
			'port' => 3306,
			'charset' => 'utf8',
			// driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
			'option' => [
					PDO::ATTR_CASE => PDO::CASE_NATURAL
			]
	] );
} catch ( Exception $e ) {
	//echo $e->getMessage ();
}

?>
