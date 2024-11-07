<?php
error_reporting(0);
include '../../koneksi1.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nopo';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
//$kapal = $_POST['kapal'];
//$novoy = $_POST['novoy'];
$nopo = $_POST['nopo'];
$noinv = $_POST['noinv'];

$offset = ($page-1) * $rows;
$panjangi = strlen($noinv);

$text = "select * from detailin where nopo='$nopo' and inv='N'
	ORDER BY $sort $order
	LIMIT $rows OFFSET $offset";

$result = array();
$result['total'] = mysql_num_rows(mysql_query("select * from detailin where nopo='$nopo' and inv='N'"));
$row = array();

$criteria = mysql_query($text);
while($data=mysql_fetch_array($criteria))
{
	$part = $data['partnumber'];
	$noid = $data['urut'];
	$panjang_id = strlen($noid);
	$tds = mysql_fetch_array(mysql_query("select * from mbarang where partnumber='$part'"));
	$nama = $tds['descript'];
		$row[] = array(
		'nopo'=>$data['nopo'],
		'qty'=>$data['qty'],
		'harga'=>$data['price'],
		'partnumber'=>$data['partnumber'],
		'nama'=>$nama,
		'total'=>$data['qty'] * $data['price'],
		'inv'=>$data['inv'],
		'noinv'=>$noinv,
		'panjangi'=>$panjangi,
		'urut'=>$data['urut'],
		'lenid'=>$panjang_id,
	);
}
$result=array_merge($result,array('rows'=>$row));
echo json_encode($result);

?>
