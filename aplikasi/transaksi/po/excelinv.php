<?php
session_start();
error_reporting(0);
include '../../koneksi1.php';
include '../../Classes/PHPExcel.php';
include '../../terbilang.php';
include '../../phpqrcode/qrlib.php';

 $akses = isset($_POST['notrans'])? mysql_real_escape_string($_POST['notrans']) : '';
 $tgl=date('d-m-Y H:i:s');
 $username = $_POST['username'];
 $kodesupp = $_POST['kodesupp'];
 $nomor = substr($akses,0,6);
 $dok = mysql_query("Select * from dokumen where kode='PO'");
 $hasildok = mysql_fetch_array($dok);
 $pt = $_SESSION['cabang'];
 if ($pt == 'IGSO'){
    $namapt = 'PT. INTI GLOBAL SENTOSA';
 }else if ($pt == 'IGSM'){
    $namapt = 'CV. INTI GLOBAL SAFETY MARINE';
 }else{
    $namapt = 'PT. INTI GLOBAL JAYA MAKMUR';
 }
 $nomordok = $hasildok['nomor'];
 $sql = "select headerpo.tgltrans,headerpo.kodesupp,headerpo.cabang,detailpo.* from headerpo,detailpo where
		concat(headerpo.notrans,'/',headerpo.suffixtrans)= detailpo.notrans
		and concat(headerpo.notrans,'/',headerpo.suffixtrans) = '" .$akses. "'";

 $result = mysql_query ($sql) or die (mysql_error ());
 $register = mysql_num_rows ($result);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D2:F3');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:C3');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B4:C4');

$objPHPExcel->getActiveSheet()->getStyle('D2:F7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2:D4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A11:F11')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B2:C4')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("45");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("13");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("15");

$objPHPExcel->getActiveSheet()->getStyle('D2:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C2:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A11:F11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A11:F11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8E5E5');
//$objPHPExcel->getActiveSheet()->getStyle('A11:F11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle("A11:F11")->applyFromArray(
	array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("D2", 'PURCHASE ORDER')
            ->setCellValue("D5", 'TANGGAL')
            ->setCellValue("D6", 'NO. PO')
      			->setCellValue("D7", 'SUPPLIER')
      			->setCellValue("E10", 'NO. FORM')
            ->setCellValue("A11", 'NO')
     			  ->setCellValue("B11", 'PART#')
       			->setCellValue("C11", 'NAMA BARANG')
            ->setCellValue("D11", 'QTY')
      			->setCellValue("E11", 'HARGA')
      			->setCellValue("F11", 'TOTAL');

   $i = 12;
   $m = 1;
   $baris = $register + 12;
   $baris1 = $baris + 3;
   $baris2 = $baris1 + 4;
   $baris3 = $baris + 1;


   while ($register = mysql_fetch_array ($result)) {
   //$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$i:D$i");
  // $objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$i:F$i");
   $tanggal = date('d-m-Y',strtotime($register['tgltrans']));
   $waktu = date('d-m-Y H:m:s');
   $kodesupp = $register['kodesupp'];
   $partnumber = $register['partnumber'];
   $cabang = $register['cabang'];
   $notrans = $register['notrans'].'/'.$register['suffixtrans'];
   $sql1 =mysql_query("select * from mvendor where kode='$kodesupp'");
   $data = mysql_fetch_array($sql1);
   $nama = $data['nama'];

   $sql2 = mysql_query("select * from mbarang where partnumber='$partnumber'");
   $data1 = mysql_fetch_array($sql2);
   $namabarang = $data1['descript'];
   $satuan = $register['satuan'];
   $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("E5", ": " . $tanggal)
            ->setCellValue("E6", ": " . $register['notrans'])
            ->setCellValue("E7", ": " . $register['kodesupp'].' - '.$nama)
            ->setCellValue("F10", ": " . $nomordok)
            ->setCellValue("A$i", $m)
            ->setCellValue("B$i", $register['partnumber'])
            ->setCellValue("C$i", $namabarang)
            ->setCellValue("D$i", $register['qty']." ".$satuan)
            ->setCellValue("E$i", $register['price'])
            ->setCellValue("F$i", $register['qty']*$register['price']);

            $jumlah = $jumlah + ($register['qty']*$register['price']);
	$objPHPExcel->getActiveSheet()->getStyle("E$i:F$i")->getNumberFormat()->setFormatCode('#,##0');
	$objPHPExcel->getActiveSheet()->getStyle("A$i:F$i")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
 	$objPHPExcel->getActiveSheet()->getStyle("D2:F3")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			//$objPHPExcel->getActiveSheet()->getStyle('F4:F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

      $i++;
      $m++;
   }
   // Kolom untuk jumlah
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris:E$baris");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris","Total Keseluruhan");
   //$objPHPExcel->getActiveSheet()->getStyle("A$baris:F$baris")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
   $objPHPExcel->getActiveSheet()->getStyle("A$baris:F$baris")->applyFromArray(
    array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$baris",$jumlah);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris3","Printed by ".$username." at ".$waktu);

   $objPHPExcel->getActiveSheet()->getStyle("F$baris")->getFont()->setBold(true);
   $objPHPExcel->getActiveSheet()->getStyle("F$baris")->getNumberFormat()->setFormatCode('#,##0');


  // Kolom untuk nomor rekening
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris1","DIBUAT OLEH,");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$baris1","DIKETAHUI OLEH,");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris2",strtoupper($username));
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$baris2", "SUPERVISOR");


$objDrawing = new PHPExcel_Worksheet_Drawing();
//$objDrawing1 = new PHPExcel_Worksheet_Drawing();
//$objDrawing->setName('Media Kreatif Indonesia');
//$objDrawing->setDescription('Logo Media Kreatif');
//$objDrawing->setPath('../../images/icon.png');
//$objDrawing->setCoordinates('A2');
//$objDrawing->setHeight(74);
//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//$paramid = 'Notrans : '.$notrans.' Plat Mobil : '.$nama.' Jumlah : '.$jumlah;
//QRcode::png($paramid);
//$objDrawing1->setPath('<img src="qrcode.php?id='.$paramid.'" />');
//$objDrawing1->setCoordinates('A5');
//$objDrawing1->setHeight(74);
//$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet());
$namaf = "PO ".$nomor." ".$pt." ".$kodesupp.".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$namaf);
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;

?>
