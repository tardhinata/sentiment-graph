<?php
require 'medoo.php';

// application config
$appaddress = 'http://taufan.site90.net/citation';

// mysql config
$dbtype = 'mysql';
$dbname = 'a6606969_db';
$hostname = 'mysql3.000webhost.com';
$username = 'a6606969_db';
$password = '123qweasd';

// Database Connection using Medoo framework
try {
	$db = new medoo ( array( 
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
			'option' => array( 
					PDO::ATTR_CASE => PDO::CASE_NATURAL 
			)
	) );
} catch ( Exception $e ) {
	echo $e->getMessage ();
}

?>