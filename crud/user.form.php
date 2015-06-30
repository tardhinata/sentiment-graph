<?php 
require '../configPDO.php';
   
$id_user = $_POST['id']; 
$data = $db->select("application_user","*",["id_user" => $id_user]);
		  
// if id_user > 0 it means edit data
if($id_user> 0) { 
	$username 	= $data[0]['username'];
	$password 	= $data[0]['password'];
	$email 		= $data[0]['email'];
	$name 		= $data[0]['name'];
	$address 	= $data[0]['address'];
	$affiliation = $data[0]['affiliation'];
	$kd_admin 	= $data[0]['admin'];
	if($data[0]['admin']==1) {
		$admin = "Admin";
	} else {
		$admin = "Non-Admin";
	}
//form add data
} else {
	$username ="";
	$password ="";
	$email ="";
	$name ="";
	$address ="";
	$affiliation ="";
	$admin = "";
}

?>
<form class="form-horizontal" id="form-user">
	<div class="control-group">
		<label class="control-label" for="username">Username</label>
		<div class="controls">
			<input type="text" id="username" class="input-medium" name="username" value="<?php echo $username ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password">Password</label>
		<div class="controls">
			<input type="text" id="password" class="input-medium" name="password" value="<?php echo $password ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="email">Email</label>
		<div class="controls">
			<input type="text" id="email" class="input-medium" name="email" value="<?php echo $email ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input type="text" id="name" class="input-xlarge" name="name" value="<?php echo $name ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address">Address</label>
		<div class="controls">
			<textarea id="address" name="address"><?php echo $address ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="affiliation">Affiliation</label>
		<div class="controls">
			<select class="input-medium" name="affiliation">
				<?php  
				if($id_user > 0) { ?>
					<option value="<?php echo $affiliation ?>"><?php echo $affiliation ?></option>
				<?php } ?>
				<option value="Isern">Isern</option> 
				<option value="Non-Isern">Non-Isern</option> 
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="admin">Admin</label>
		<div class="controls">
			<select class="input-medium" name="admin">
				<?php  
				if($id_user > 0) { ?>
					<option value="<?php echo $kd_admin ?>"><?php echo $admin ?></option>
				<?php } ?>
				<option value="1">Admin</option>
				<option value="0">Non-Admin</option>
			</select>
		</div>
	</div> 
</form> 
		
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$('#form-user input').hover(function()
	{
		$(this).popover('show')
	});
	
	$("#form-user").validate({
		rules:{
			username:"required",
			email:{
					required:true,
					email: true
				},
			password:{
				required:true,
				minlength: 6
			}, 
			name:"required"
		},
		messages:{
			username:"Username required",
			email:{
				required:"Email address required",
				email:"Email is invalid"
			},
			password:{
				required:"Password required",
				minlength:"Password must be minimum 6 characters"
			}, 
			name:"Name required"
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
});
</script>
	   
