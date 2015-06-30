<html>
<head>
	<title>Bank Of Ri32 Syariah</title>
    <link rel="stylesheet" href="tabel.css" />
</head>
<body>
<a href="index.php"><font color="blue">Home</font></a> ||
<a href="?page=data_nasabah_1"><font <?php if($_GET['page']=='data_nasabah_1'){ echo "color=red"; }else{ echo "color=blue";}?>>Tanpa Paging</font></a> ||
<a href="?page=data_nasabah_2"><font <?php if($_GET['page']=='data_nasabah_2'){ echo "color=red"; }else{ echo "color=blue";}?>>Dengan Paging</font></a> ||
<a href="?page=data_nasabah_3"><font <?php if($_GET['page']=='data_nasabah_3'){ echo "color=red"; }else{ echo "color=blue";}?>>Searching Paging</font></a>
<?php
	$page="";
	if(isset($_GET['page'])){
		$page=$_GET['page'];
	}
	
	if(empty($page)){
		echo "<br>Assalamu'alaikum";
		echo "<br>Selamat Datang di Bank RI32 Syariah";
		echo "<br><img src='logo-ri32.jpg' height='130' width='150'>";
	}else{
		$file="$page.php";
		include ($file);
	}	
?>
</body>
</html>