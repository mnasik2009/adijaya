<?php
error_reporting(0);
session_start();
include '../../koneksi1.php';
include '../../Classes/PHPExcel.php';
include '../../terbilang.php';
include '../../phpqrcode/qrlib.php';
include '../../englishT.php';

 $akses = isset($_POST['noinvoice'])? mysql_real_escape_string($_POST['noinvoice']) : '';
 $customer = isset($_POST['customer'])? mysql_real_escape_string($_POST['customer']) : '';
 $tgl=date('d-m-Y H:i:s');
 $username = $_POST['username'];
 $port = $_POST['deskripsi'];
 $diskon = $_POST['diskon'];
 $ppn = $_POST['ppn'];
 $transport = $_POST['transport'];

 $pt = $_SESSION['cabang'];
 if ($pt == 'IGSO'){
    $namapt = 'PT. INTI GLOBAL SENTOSA';
 }else if ($pt == 'IGSM'){
    $namapt = 'CV. INTI GLOBAL SAFETY MARINE';
 }else{
    $namapt = 'PT. INTI GLOBAL JAYA MAKMUR';
 }
 $cust = mysql_query("Select * from mvendor where kode='$customer'");
 $hasilcust = mysql_fetch_array($cust);
 $namacust = $hasilcust['nama'];
 $alamat = $hasilcust['alamat'];
 $kota = $hasilcust['kota'];
 $pic = $hasilcust['pic'];
 $creditterm = $hasilcust['creditterm'];
 $npwp = $hasilcust['npwp'];
 $sql = "select invoice.noinvoice, invoice.customer, invoice.tglinv, invoice.keterangan, invoice.nopo, detinv.*
		from invoice,detinv
        where invoice.noinvoice = detinv.noinv
        and invoice.noinvoice ='" .$akses. "'";

 $result = mysql_query ($sql) or die (mysql_error ());
 $register = mysql_num_rows ($result);

 $tempdir = "temp/";
 if (!file_exists($tempdir))
		mkdir($tempdir);
 $isi = substr($akses,0,6);
 $namafile = $isi.'.png';
 $qua = 'H';
 $ukuran = 5;
 $padding = 0;
 QRCode::png($akses,$tempdir.$namafile,$qua,$ukuran,$padding);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:G5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D14:E14');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A9:E11');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C9:G9');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:G11');
$objPHPExcel->getActiveSheet()->getStyle("A3:G3")->applyFromArray(
    array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE))));

//$objPHPExcel->getActiveSheet()->getStyle('G6:G7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2:C3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A14:G14')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
$objPHPExcel->getActiveSheet()->getStyle('C2:C3')->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
$objPHPExcel->getActiveSheet()->getStyle('C4:D10')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->SetName('Bodoni MT Black');
$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->SetName('Monotype Corsiva');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("5");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("10");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("10");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("25");

//$objPHPExcel->getActiveSheet()->getStyle('G6:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A14:G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A14:G14')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8E5E5');
//$objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('A9:E11')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A14:G14")->applyFromArray(
    array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));

 $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", $namafile)
            ->setCellValue("A2", 'Jl. Cempaka Raya No. 07 RT. 05 Banjar barat, Banjarmasin - South Kalimantan 70119')
            ->setCellValue("A3", 'Jl. DI Panjaitan Perum Talang Sari Regency Ruko No. 02 / B RT. 04 Samarinda - East Kalimantan 75119')
            ->setCellValue("A5", 'I N V O I C E')
			->setCellValue("A7", 'To Messr,')
            ->setCellValue("F7", 'Invoice Date')
            ->setCellValue("F8", 'Invoice No.')
			->setCellValue("F9", 'PO / SC No.')
            ->setCellValue("F10", 'Supply To')
            ->setCellValue("F11", 'Port')
            ->setCellValue("F12", 'Attn')
            ->setCellValue("A14", 'NO')
 			->setCellValue("B14", 'DESCRIPTION')
   			//->setCellValue("C20", 'PART')
        //    ->setCellValue("D20", 'NAMA PART')
			->setCellValue("D14", 'QUANTITY')
			->setCellValue("F14", 'PRICE')
            ->setCellValue("G14", 'A M O U N T');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B14:C14");
  
   $i = 15;
   $m = 1;
   $baris = $register + 15;
   $baris1 = $baris + 1;
   $baris2 = $baris1 + 1;
   $baris2a = $baris2 + 1;
   $baris2b = $baris2a + 1;
   $baris3 = $baris2b + 1;
   $baris4 = $baris3 + 2;
   $baris44 = $baris4 + 1;
   $baris4a = $baris44 + 1;
   $baris5 = $baris44 + 2;
   $baris6 = $baris5 + 1;
   $baris7 = $baris6 + 1;
   $baris8 = $baris7 + 1;
   $baris9 = $baris8 + 1;
   $baris10 = $baris9 + 1;
   $baris11 = $baris10 + 1;
   while ($register = mysql_fetch_array ($result)) {
	$part = $register['partnumber'];
    $nopo = $register['nopo'];

    if (substr($nopo,12,3)== 'SMD'){
        $cab = 'Samarinda';
        $pim = 'W A S I R A N';
    }else if (substr($nopo,12,3)== 'BJM'){
        $cab = 'Banjarmasin';
        $pim = 'B U R H A N I';
    }else{
        $cab = 'Kendari';
        $pim = 'NUR ASIKA BASNAN';
    }
	$dtpart = mysql_fetch_array(mysql_query("select * from mbarang where partnumber = '$part'"));
   $tanggal = date('F j, Y',strtotime($register['tglinv']));
   //$vendor = $register['penerima'];
   $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A8", $namacust)
            ->setCellValue("A9", $alamat)
            ->setCellValue("A12", "NPWP : ".$npwp)
			->setCellValue("G7", ": " . $tanggal)
            ->setCellValue("G8", ": " . $register['noinvoice'])
			->setCellValue("G9", ": " . $register['nopo'])
            ->setCellValue("G10", ": " . $register['keterangan'])
            ->setCellValue("G11", ": " . $port)
            ->setCellValue("G12", ": " . $pic)
			->setCellValue("A$i", $m)
			->setCellValue("B$i", $dtpart['descript'])
            //->setCellValue("C$i", $register['partnumber'])
            //->setCellValue("D$i", $dtpart['descript'])
            ->setCellValue("D$i", $register['qty'])
            ->setCellValue("E$i", "PCS")
            ->setCellValue("F$i", $register['price'])
            ->setCellValue("G$i", $register['total']);
		    $nilai = $nilai + $register['total'];
			$objPHPExcel->getActiveSheet()->getStyle("F$i:G$i")->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$i:C$i");
			//$objPHPExcel->getActiveSheet()->getStyle("A$i:G$i")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("A$i:G$i")->applyFromArray(
				array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));

      $i++;
      $m++;
   }
   $ppn1 = $nilai * 0.11;
   $diskon1 = $nilai * ($diskon/100);
   $subtotal = $nilai - $diskon;
   $total = $subtotal + $ppn + $transport;
   // Kolom untuk jumlah
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris:F$baris");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris1:F$baris1");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris2:F$baris2");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris2a:F$baris2a");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris2b:F$baris2b");
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A$baris3:F$baris3");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris","Total");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris1","Diskon (".$diskon."%)");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris2","Sub Total");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris2a","VAT (11%)");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris2b","Transportation");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$baris3","Grand Total");
   $objPHPExcel->getActiveSheet()->getStyle("A$baris:A$baris3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
   //$objPHPExcel->getActiveSheet()->getStyle("A$baris:I$baris3")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
   $objPHPExcel->getActiveSheet()->getStyle("A$baris:G$baris3")->applyFromArray(
    array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris",$nilai);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris1",$diskon1);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris2",$subtotal);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris2a",$ppn1);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris2b",$transport);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris3",$total);
   $objPHPExcel->getActiveSheet()->getStyle("G$baris")->getFont()->setBold(true);
   $objPHPExcel->getActiveSheet()->getStyle("G$baris3")->getFont()->setBold(true);
   $objPHPExcel->getActiveSheet()->getStyle("E$baris:G$baris3")->getNumberFormat()->setFormatCode('#,##0');

   $sqlglobal = "select * from globalset where left(kode,4)='$pt'";
   $hasil = mysql_fetch_array(mysql_query($sqlglobal));
   $accname = $hasil['pemilikrek'];
   $accno = $hasil['norek'];
   $accbank = $hasil['bank'];
   $acchead = $hasil['pimpinan'];
   $kode = $hasil['kode'];
   // kolom untuk terbilang
   $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$baris44:F$baris44");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris4","Term Of Payment  : ".($creditterm). " Days");
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris44","Said  : ".CurencyLang::toEnglish($total). " Rupiah");
   $objPHPExcel->getActiveSheet()->getStyle("B$baris4:C$baris44")->getFont()->setBold(true);
   $objPHPExcel->getActiveSheet()->getStyle("A$baris4:C$baris44")->getFont()->setSize(11);
   $objPHPExcel->getActiveSheet()->getStyle("B$baris4:C$baris44")->getFont()->setItalic(true);
   $objPHPExcel->getActiveSheet()->getStyle("C$baris4")->getFont()->SetName('Brush Script MT');
   $objPHPExcel->getActiveSheet()->getStyle("C$baris4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

  // Kolom untuk nomor rekening
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris5","Please Banking Telegraphic Transfer to: ");
   $objPHPExcel->getActiveSheet()->getStyle("B$baris6:B$baris8")->getFont()->setBold(true);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris6","A/C No. : ".$accno);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris7",$accname);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris8", $accbank);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris4", $cab.", ".$tanggal);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris4a", $accname);

   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$baris6",": " .$accbank);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$baris7",": " .$accname);
   //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$baris8",": " .$accno);
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$baris9",$pim); 

   mysql_query("update invoice set diskon='$diskon', ppn='$ppn', nilaidiskon='$diskon1', nilaippn = '$ppn1', transport='$transport' where noinvoice='$akses'");
   mysql_query("update postinv set diskon='$diskon1', ppn = '$ppn1', transport='$transport' where noinv='$akses'");
	// garis B6:D9
    //$objPHPExcel->getActiveSheet()->getStyle("A9:C9")->applyFromArray( 
    //    array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objDrawing = new PHPExcel_Worksheet_Drawing(); 
$objDrawing1 = new PHPExcel_Worksheet_Drawing();
//$objDrawing->setName('Media Kreatif Indonesia');
//$objDrawing->setDescription('Logo Media Kreatif');
if ($pt == 'IGSO'){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", 'PT. INTI GLOBAL SENTOSA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris10", 'BANK BSI (Bank Syariah Indonesia)');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$baris11", 'A/C No.: 1210197625');
    $objPHPExcel->getActiveSheet()->getStyle("B$baris11")->getFont()->setBold(true);
}else if  ($pt == 'IGSM'){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", 'CV. INTI GLOBAL SAFETY MARINE');
}else{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", 'PT. INTI GLOBAL JAYA MAKMUR');
}

//$objDrawing->setCoordinates('A1');
//$objDrawing->setHeight(100);
//$objDrawing->setWidth(960);
//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$namaf = $isi." ".$pt." ".$customer.".xlsx";
$objDrawing1->setPath('temp/'.$isi.'.png');
$objDrawing1->setCoordinates("D$baris5");
$objDrawing1->setHeight(74);
$objDrawing1->setOffsetX(100);
$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet());

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$namaf);
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
unlink('temp/'.$isi.'.png');
exit;

?>
