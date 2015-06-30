<?php
session_start();
include("configPDO.php");
 
$UserName=$_POST['inputName']; 
$Password=$_POST['inputPassword'];  
  
// check credential
$data = $db->select("application_user",["username","admin"],
			["AND" =>["username" => $UserName, "password" => $Password]
		]);
  
// redirect to admin page
if(count($data)==1) 
{ 
	$_SESSION['myusername']=$UserName;  
	if($data[0]['admin'] == 1)	 
	{ 
		$_SESSION['admin']=$data[0]['admin'];  
		header( "location:".$appaddress."/admin.php"); 	
	}
	else 
	{
		header( "location:".$appaddress."/index.php");  
	}
}
else 
{
	header("location:".$appaddress."/index.php");
}
 
// Closing MySQL database connection   
$dbh = null;	
?>

