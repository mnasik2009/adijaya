<?php
error_reporting(0);
session_start();
include '../../koneksi1.php';

$cabang = $_SESSION['cabang'];
$partnumber = $_POST['partnumber'];
$descript = $_POST['descript'];
$stock = $_POST['stock'];
$price = $_POST['price'];
$aktif = $_POST['aktif'];
$satuan = $_POST['satuan'];

$row = mysql_num_rows(mysql_query("SELECT * FROM mbarang WHERE partnumber='$partnumber'"));
if($row>0){
	$text = "UPDATE mbarang SET descript='$descript',
								price='$price',
								stock='$stock',
								satuan='$satuan',
								aktif = '$aktif'
			WHERE partnumber='$partnumber'";
	mysql_query($text);
	echo "Update Sukses";
}else{
	$text = "INSERT INTO mbarang SET partnumber='$partnumber',
								descript='$descript',
								price='$price',
								stock = '$stock',
								satuan='$satuan',
								aktif = '$aktif'";
	mysql_query($text);
	echo "Simpan Sukses";
}
?>
