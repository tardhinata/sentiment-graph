<?php
require '../configPDO.php';
   
if(isset($_POST['delete'])) 
{ 
	$db->delete("application_user", [ 
			"id_user" => $_POST['delete']
	]); 
}
else
{ 
	$id_user		= $_POST['id'];
	$username		= $_POST['username'];
	$password		= $_POST['password'];
	$email			= $_POST['email'];
	$name			= $_POST['name'];
	$address		= $_POST['address'];
	$affiliation	= $_POST['affiliation'];
	$admin 		= $_POST['admin'];
	
	// validasi agar tidak ada data yang kosong
	if($email!="" && $name!="" && $address!="") {
		// proses add data user
		if($id_user == 0) {
			$db->query("INSERT INTO application_user VALUES('','$username','$password','$email','$name','$address','$affiliation','$admin')");
		// proses edit data user
		} else {
			$db->query("UPDATE application_user SET 
			username = '$username',
			password = '$password',
			email = '$email',
			name = '$name',
			address = '$address',
			affiliation = '$affiliation',
			admin = '$admin'
			WHERE id_user = $id_user
			");
		}
	}
} 
?>
