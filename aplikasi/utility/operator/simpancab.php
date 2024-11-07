<?php
error_reporting(0);
session_start();

include '../../koneksi1.php';

$username = $_POST['username'];
$cabang = $_POST['cab'];

unset($_SESSION['cabang']);
$_SESSION['cabang'] = $cabang;
echo '<script type="text/javascript">alert("Data has been saved Successfully");</script>';
?><script language="javascript">document.location.href="https://intiglobal-group.com/aplikasi/index.php?view=setcabang";</script>
