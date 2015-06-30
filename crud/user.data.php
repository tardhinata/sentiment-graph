<?php 
require '../configPDO.php'; 
?>

<table class="table table-condensed table-bordered table-hover" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th style="width:20px">#</th>
		<th style="width:120px">Username</th>
		<th style="width:120px">Password</th>
		<th style="width:120px">Email</th>
		<th style="width:150px">Name</th>
		<th>Address</th>
		<th style="width:80px">Affiliation</th>
		<th style="width:70px">Admin</th>
		<th style="width:40px">Action</th>
	</tr>
</thead>
<tbody>
	<?php 
		$i = 1;
		$query = $db->query("SELECT * FROM application_user")->fetchAll();   
		foreach ($query as $data) { 
			if($data['admin']==1) {
				$admin = "Admin";
			} else {
				$admin = "Non-Admin";
			}
	?>
	<tr>
		<td><?php echo $i ?></td>
		<td><?php echo $data['username'] ?></td>
		<td><?php echo $data['password'] ?></td>
		<td><?php echo $data['email'] ?></td>
		<td><?php echo $data['name'] ?></td>
		<td><?php echo $data['address'] ?></td>
		<td><?php echo $data['affiliation'] ?></td>
		<td><?php echo $admin ?></td>
		<td>
			<a href="#dialog-user" id="<?php echo $data['id_user'] ?>" class="edit" data-toggle="modal">
				<i class="icon-pencil"></i>
			</a>
			<a href="#" id="<?php echo $data['id_user'] ?>" class="delete">
				<i class="icon-trash"></i>
			</a>
		</td>
	</tr>
	<?php
		$i++;
		}
	?>
</tbody>
</table> 

