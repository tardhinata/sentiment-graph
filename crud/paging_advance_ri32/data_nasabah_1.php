<html>
<body onLoad="document.postform.elements['nasabah'].focus();">
<?php  
//untuk koneksi database
include "koneksi.php";

//untuk menantukan tanggal awal dan tanggal akhir data di database
$min_tanggal=mysql_fetch_array(mysql_query("select min(tanggal) as min_tanggal from tabel_nasabah"));
$max_tanggal=mysql_fetch_array(mysql_query("select max(tanggal) as max_tanggal from tabel_nasabah"));
?>

<form action="?page=data_nasabah_1" method="get" name="postform">
<table width="435" border="0">
<tr>
    <td width="111">Nama Nasabah</td>
    <td colspan="2"><input type="text" name="nasabah" value="<?php  if(isset($_GET['nasabah'])){ echo $_GET['nasabah']; }?>"/></td>
</tr>
<tr>
    <td>Tanggal Awal</td>
    <td colspan="2"><input type="text" name="tanggal_awal" size="15" value="<?php  echo $min_tanggal['min_tanggal'];?>"/>
    <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal_awal);return false;" ><img src="calender/calender.jpeg" alt="" name="popcal" width="34" height="29" border="0" align="absmiddle" id="popcal" /></a>				
    </td>
</tr>
<tr>
    <td>Tanggal Akhir</td>
    <td colspan="2"><input type="text" name="tanggal_akhir" size="15" value="<?php  echo $max_tanggal['max_tanggal'];?>"/>
    <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tanggal_akhir);return false;" ><img src="calender/calender.jpeg" alt="" name="popcal" width="34" height="29" border="0" align="absmiddle" id="popcal" /></a>				
    </td>
</tr>
<tr>
    <td>
	<!--by pass parameter page untuk metod get-->
	<input type="hidden" value="data_nasabah_1" name="page">
	<input type="submit" value="Tampilkan Data" name="cari">
	</td>
    <td colspan="2">&nbsp;</td>
</tr>
</table>
</form>

<p>
<?php  
//di proses jika sudah klik tombol cari
if((isset($_GET['cari']))){
	
	//menangkap nilai URL
	$nasabah=$_GET['nasabah'];
	$tanggal_awal=$_GET['tanggal_awal'];
	$tanggal_akhir=$_GET['tanggal_akhir'];
	
	if(empty($nasabah)){
		//jika tidak menginput data nasabah tampilkan semua data nasabah
		$query=mysql_query("select * from tabel_nasabah");
		$jumlah=mysql_fetch_array(mysql_query("select sum(uang) as total from tabel_nasabah"));
		$cetak=$jumlah['total'];
	}else{
		$query=mysql_query("select * from tabel_nasabah where nama_nasabah like '%$nasabah%' and tanggal between '$tanggal_awal' and '$tanggal_akhir'");
		$jumlah=mysql_fetch_array(mysql_query("select sum(uang) as total from tabel_nasabah where nama_nasabah like '%$nasabah%' and tanggal between '$tanggal_awal' and '$tanggal_akhir'"));
		$cetak=$jumlah['total'];	 
	}
		//tampilkan informasi pencarian
		?><i>Jumlah Record : <?php echo mysql_num_rows($query);?><br><b> Informasi : </b> Pencarian nama nasabah <b><?php  echo ucwords($nasabah);?></b> dari tanggal <b><?php  echo $tanggal_awal;?></b> sampai dengan tanggal <b><?php  echo $tanggal_akhir; ?></b></i><?php 

}else{
	//paging saat halaman pertama dibuka
	$query=mysql_query("select * from tabel_nasabah");
	$jumlah=mysql_fetch_array(mysql_query("select sum(uang) as total from tabel_nasabah"));
	$cetak=$jumlah['total'];
	?><i>Jumlah Record : <?php echo mysql_num_rows($query);
}
?>
</p>

<table class="datatable">
	<tr>
    	<th width="34">No</th>
    	<th width="90">Tanggal</th>
    	<th width="131">Nama Nasabah</th>
    	<th width="104">Uang (Rp)</th>
    </tr>
	<?php  //untuk penomoran data
	$no=0;
	
	//menampilkan data
	while($row=mysql_fetch_array($query)){
	?>
    <tr>
    	<td><?php  echo $offset=$offset+1; ?></td>
		<td><?php  echo $row['tanggal']; ?></td>
		<td><?php  echo $row['nama_nasabah'];?></td>
		<td align="right"><?php  echo number_format($row['uang'],2,',','.');?></td>
    </tr>
    <?php  }
	?>
    <tr>
    	<td colspan="3" align="right"><strong>TOTAL</strong></td><td align="right"><?php  echo number_format($cetak,2,',','.');?></td>
    </tr>
    
    <tr>
    	<td colspan="4" align="center"> 
		<?php  //jika data tidak ditemukan
		if(mysql_num_rows($query)==0){
			echo "<font color=red><blink>Tidak ada data!</blink></font>";
		}
		?>
        </td>
    </tr>
</table>

<iframe width=174 height=189 name="gToday:normal:calender/normal.js" id="gToday:normal:calender/normal.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
</body>
</html>