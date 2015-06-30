<?php
include("logger.php");
session_start(); 
function isLoginAsAdmin(){
	if(isset($_SESSION['myusername']) && isset($_SESSION['admin']))
		return true;
	else
		return false;
} 
?>