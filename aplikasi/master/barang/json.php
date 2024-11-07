<?php
error_reporting(0);
include '../../koneksi1.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'partnumber';
$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';

$offset = ($page-1) * $rows;

$where = " WHERE aktif='Y' and (partnumber LIKE '%$cari%' OR descript LIKE '%$cari%')";

$text = "SELECT * FROM mbarang
	$where
	ORDER BY $sort $order
	LIMIT $rows OFFSET $offset";

$result = array();
$result['total'] = mysql_num_rows(mysql_query("SELECT * FROM mbarang $where"));
$row = array();

$criteria = mysql_query($text);
while($data=mysql_fetch_array($criteria))
{
	$row[] = array(
		'partnumber'=>$data['partnumber'],
		'descript'=>$data['descript'],
		'stock'=>$data['stock'],
		'price'=>$data['price'],
		'aktif'=>$data['aktif'],
		'satuan'=>$data['satuan'],
	);
}
$result=array_merge($result,array('rows'=>$row));
echo json_encode($result);
?>
