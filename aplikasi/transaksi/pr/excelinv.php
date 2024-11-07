<?php
session_start();
error_reporting(0);

include '../../koneksi1.php';
include '../../Classes/PHPExcel.php';
include '../../terbilang.php';
include '../../phpqrcode/qrlib.php';

 $akses = isset($_POST['notrans'])? mysql_real_escape_string($_POST['notrans']) : '';
 $cabang = substr($akses,12,3);
 $nomor = substr($akses,0,6);
 $kodesupp = $_POST['kodesupp'];
 $tgl=date('d-m-Y H:i:s');
 $username = $_POST['username'];
 $dokcab = mysql_fetch_array(mysql_query("Select * from cabang where kode='$cabang'"));
 $alamatcab = $dokcab['alamat'];
 $telp = $dokcab['telepon'];
 $email = $dokcab['email'];
 $hasildok = mysql_fetch_array($dok);
 $pt = $_SESSION['cabang'];
 if ($pt == 'IGSO'){
    $namapt = 'PT. INTI GLOBAL SENTOSA';
 }else if ($pt == 'IGSM'){
    $namapt = 'CV. INTI GLOBAL SAFETY MARINE';
 }else{
    $namapt = 'PT. INTI GLOBAL JAYA MAKMUR';
 }
 $sql = "select headerin.tgltrans,headerin.kodesupp,headerin.kirim, headerin.kapal, headerin.remarks, detailin.* from headerin,detailin where
		concat(headerin.notrans,'/',headerin.suffixtrans) = detailin.notrans
		and concat(headerin.notrans,'/',headerin.suffixtrans) = '" .$akses. "'";

 $result = mysql_query ($sql) or die (mysql_error ());
 $register = mysql_num_rows ($result);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:J3');

$objPHPExcel->getActiveSheet()->getStyle('G2:J7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2:D4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C3:C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A15:E15')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->SetName('Arial Narrow');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getStyle('A3:A4')->getFont()->SetName('Arial Narrow');
$objPHPExcel->getActiveSheet()->getStyle('G2')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("35");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("15");

$objPHPExcel->getActiveSheet()->getStyle('G2:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G2:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C2:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A15:E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A15:E15')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8E5E5');
$objPHPExcel->getActiveSheet()->getStyle("A15:E15")->applyFromArray(
	array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
//$objPHPExcel->getActiveSheet()->getStyle('B6:D9')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:E4")->applyFromArray(
   array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A6:E6");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A7:E7");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B15:C15");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2",$namapt);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3",$alamatcab);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","Telepon : ".$telp. " E-mail : ".$email);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A6", 'DELIVERY ORDER')
            ->setCellValue("A7", $akses)
            ->setCellValue("A9", 'DATE')
            ->setCellValue("A10", 'CUSTOMER')
			   ->setCellValue("A11", 'PO. NUMBER')
            ->setCellValue("A12", 'VESSEL')
            ->setCellValue("A13", 'PORT')

            ->setCellValue("A15", 'NO')
   			->setCellValue("B15", 'DESCRIPTION')
            ->setCellValue("D15", 'QTY')
			   ->setCellValue("E15", 'REMARKS');

   $i = 16;
   $m = 1;
   $baris = $register + 16;
   $baris1 = $baris + 3;
   $baris2 = $baris1 + 4;
   $baris3 = $baris2 + 1;


   while ($register = mysql_fetch_array ($result)) {
   //$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$i:D$i");
  // $objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$i:F$i");
   $tanggal = date('d-m-Y',strtotime($register['tgltrans']));
   $waktu = date('d-m-Y H:m:s');
   $kodesupp = $register['kodesupp'];
   $partnumber = $register['partnumber'];
   $notrans = $register['notrans'].'/'.$register['suffixtrans'];
   $sql1 =mysql_query("select * from mvendor where kode='$kodesupp'");
   $data = mysql_fetch_array($sql1);
   $nama = $data['nama'];
   $sql2 = mysql_query("select * from mbarang where partnumber='$partnumber'");
   $data1 = mysql_fetch_array($sql2);
   $namabarang = $data1['descript'];
   $satuan = $data1['satuan'];
   $objPHPExcel->setActiveSheetIndex(0)
      			->setCellValue("C9", ": " . $tanggal)
               ->setCellValue("C10", ": " . $register['kodesupp'].' - '.$nama)
      			->setCellValue("C11", ": " . $register['remarks'])
               ->setCellValue("C12", ": " . $register['kapal'])
               ->setCellValue("C13", ": " . $register['kirim'])

      			->setCellValue("A$i", $m)
      			->setCellValue("B$i", $namabarang)
               ->setCellValue("D$i", $register['qty']." ".$satuan);

            $jumlah = $jumlah + ($register['qty']*$register['price']);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$i:C$i");
			   $objPHPExcel->getActiveSheet()->getStyle("D$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("A$i:E$i")->applyFromArray(
                array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			//$objPHPExcel->getActiveSheet()->getStyle("A$i:E$i")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
 		    //$objPHPExcel->getActiveSheet()->getStyle("G2:J3")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			//$objPHPExcel->getActiveSheet()->getStyle('F4:F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

      $i++;
      $m++;
   }
   // Kolom untuk jumlah
   //$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$baris:C$baris");
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris","Total Keseluruhan");
   //$objPHPExcel->getActiveSheet()->getStyle("A$baris:E$baris")->applyFromArray(
	//array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
   //$objPHPExcel->getActiveSheet()->getStyle("A$baris:E$baris")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris",$jumlah);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris3","Printed by ".$username." at ".$waktu);

   //$objPHPExcel->getActiveSheet()->getStyle("F$baris")->getFont()->setBold(true);
   //$objPHPExcel->getActiveSheet()->getStyle("F$baris")->getNumberFormat()->setFormatCode('#,##0');


  // Kolom untuk nomor rekening
   $objPHPExcel->getActiveSheet()->getStyle("A$baris1:E$baris3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris1:B$baris1");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris2:B$baris2");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("D$baris1:E$baris1");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("D$baris2:E$baris2");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("D$baris3:E$baris3");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris1","Prepared By,");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$baris1","Delivered By,");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$baris1","Received By,");
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$baris2",strtoupper($username));
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris2", "____________________");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$baris2", "____________________");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$baris2", "____________________");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$baris3","Date : .... / ..... / .......");
   $objPHPExcel->getActiveSheet()->getStyle("A$baris3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->insertNewRowBefore(6, 2);
$objDrawing = new PHPExcel_Worksheet_Drawing();
//$objDrawing1 = new PHPExcel_Worksheet_Drawing();
//$objDrawing->setName('Media Kreatif Indonesia');
//$objDrawing->setDescription('Logo Media Kreatif');
//if ($pt == 'IGSO'){
//   $objDrawing->setPath('../../images/igso_all.png');
//}else if  ($pt == 'IGSM'){
//   $objDrawing->setPath('../../images/igsm_all.png');
//}else{
//   $objDrawing->setPath('../../images/igjm_all.png');
//}

//$objDrawing->setCoordinates('A1');
//$objDrawing->setHeight(100);
//$objDrawing->setWidth(625);
//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//$paramid = 'Notrans : '.$notrans.' Plat Mobil : '.$nama.' Jumlah : '.$jumlah;
//QRcode::png($paramid);
//$objDrawing1->setPath('<img src="qrcode.php?id='.$paramid.'" />');
//$objDrawing1->setCoordinates('A5');
//$objDrawing1->setHeight(74);
//$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet());
//$namaf = $akses.".xlsx";
$namaf = "DO ".$nomor." ".$pt." ".$kodesupp.".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$namaf);
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;

?>
