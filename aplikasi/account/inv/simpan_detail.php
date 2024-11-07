<?php
session_start();
include '../../koneksi1.php';
$username = $_SESSION['login_user'];

//$panjang = strlen($_POST['menuid']);

$noinv = trim(substr($_POST['menuid'],10,30));
//$pan = $panjang - 20;
$id =  trim(substr($_POST['menuid'],0,10));

$text = "SELECT * from detailin where urut='$id' and inv='N'";
$criteria = mysql_query($text);
$data=mysql_fetch_array($criteria);
    $notrans = $data['notrans'];
	$dtm = mysql_fetch_array(mysql_query("select * from headerin where concat(notrans,'/',suffixtrans)='$notrans'"));
	$tanggal = date('Y-m-d',strtotime($dtm['tgltrans']));
	$nopo = $data['nopo'];
	$partnumber = $data['partnumber'];
	$qty = $data['qty'];
	$price = $data['price'];
	$harga = $data['qty'] * $data['price'];

	$text2 = "insert into detinv set noinv='$noinv',
									 tglkirim='$tanggal',
									 partnumber='$partnumber',
									 price = '$price',
									 qty = '$qty',
									 total = '$harga',
									 nopo = '$nopo'";
	@mysql_query($text2);
	$text3 = "update detailin set inv='Y' where urut='$id'";
	@mysql_query($text3);
	@mysql_query("delete from detinv where partnumber='' and price=''");
	@mysql_query("update postinv set nilai = nilai + $harga where noinv='$noinv'");
	echo "Data Detail Berhasil disimpan $noinv";
?>
