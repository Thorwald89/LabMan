<?php

date_default_timezone_set('UTC');
require('fpdf/fpdf.php');





class PDF_result extends FPDF {
	function __construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 10) {
		$this->FPDF($orientation, $unit, $format);
		$this->SetTopMargin($margin);
		$this->SetLeftMargin($margin);
		$this->SetRightMargin($margin);

		$this->SetAutoPageBreak(true, $margin);
	}

	function Header () {
		$this->SetX(540);
	    $this->Image('unina_logo.jpg',100,15,250);

	}

 function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Testo - Testo',0,0,'C');
}


function Generate_Table($prodotto, $quota, $data_carico,$data_scarico, $op_carico,$op_scarico,$lotto,$codice,$scadenza) {

	$this->SetFont('Arial', '');
	$this->SetFillColor(238);
	$this->SetLineWidth(0.2);
	$fill = false;

//	for ($i = 0; $i < count($subjects); $i++) {
		$this->Cell(80, 20, $prodotto, 1, 0, 'L', $fill);
		$this->Cell(50, 20, $quota, 1, 0, 'L', $fill);
		$this->Cell(70, 20, $codice, 1, 0, 'L', $fill);
		$this->Cell(100, 20, $lotto, 1, 0, 'L', $fill);
		$this->Cell(100, 20, $scadenza, 1, 0, 'L', $fill);
		$this->Cell(80, 20, $data_carico, 1, 0, 'L', $fill);
		$this->Cell(80, 20, $data_scarico, 1, 0, 'L', $fill);
		$this->Cell(100, 20, $op_carico, 1, 0, 'L', $fill);
		$this->Cell(100, 20, $op_scarico, 1, 1, 'L', $fill);
		$fill = !$fill;
//	}
//	$this->SetX(367);
	//$this->Cell(100, 20, "Total", 1);
//	$this->Cell(100, 20,  array_sum($marks), 1, 1, 'R');
}






}



include('../setup.php');





$pdf = new PDF_result();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY(100);


// head DX
$pdf->SetX(440);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(200, 15, 'DAI Medicina Trasfusionale', 0, 2, 'C');
$pdf->Cell(200, 15, 'UOS Manipolazione e Criopreservazione Cellulare', 0, 2, 'C');

$pdf->Ln(10);
$pdf->SetX(440);

$pdf->SetFont('Arial', 'I');
$pdf->Cell(50, 13, "Data:");
$pdf->SetFont('Arial', '');
$pdf->Cell(100, 13, date('j-m-Y'), 0, 1);

$pdf->Ln(90);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(0);
//	$pdf->SetFillColor(94, 188, z);
	$pdf->SetFillColor(94, 188, 225);
	$pdf->SetLineWidth(1);
	$pdf->Cell(80, 25, "Prodotto", 'LTR', 0, 'C', true);
	$pdf->Cell(50, 25, "Quota", 'LTR', 0, 'C', true);
	$pdf->Cell(70, 25, "Codice", 'LTR', 0, 'C', true);
	$pdf->Cell(100, 25, "Lotto", 'LTR', 0, 'C', true);
	$pdf->Cell(100, 25, "Scadenza", 'LTR', 0, 'C', true);
	$pdf->Cell(80, 25, "Data Carico", 'LTR', 0, 'C', true);
	$pdf->Cell(80, 25, "Data Scarico", 'LTR', 0, 'C', true);
	$pdf->Cell(100, 25, "Operatore Carico", 'LTR', 0, 'C', true);
	$pdf->Cell(100, 25, "Operatore Scarico", 'LTR', 1, 'C', true);


	$s=mysql_query("select * from prodotti ") or die(mysql_error());

	while($b=mysql_fetch_array($s)){

$pdf->Generate_Table($b['prodotto'], $b['quota'], $b['data_carico'], $b['data_scarico'], $b['operatore_carico'], $b['operatore_scarico'], $b['lotto'], $b['codice'], $b['scadenza'] );
}
$pdf->Ln(50);

$message = "Esportazione conclusa. ";

$pdf->MultiCell(0, 15, $message);

$pdf->SetFont('Arial', 'U', 12);
$pdf->SetTextColor(1, 162, 232);

$pdf->Write(13, "prova@prova.com", "mailto:example@example.com");

$pdf->Output('esporta.pdf', 'F');

$now = date("d-m-Y");
$origine = "esporta.pdf";
$sposta = "../../download/esporta.pdf";
copy($origine, $sposta);
chmod('esporta.pdf', 0777);
$ren= "../../download/".$now.".pdf";
chmod($ren, 0777);

rename($sposta, $ren);



?>

<script language="javascript">
alert("Export eseguito correttamente");
</script>


<html>
<head>
<title>Laboratorio di Manipolazione Cellulare - Prodotti</title>
<link rel="stylesheet" href="../../stile.css" />

<style type="text/css">
	<!--
body {
background-color: white;

    }
-->
</style>



</head>



<body>


 <center>Troverai il file pdf nella cartella "download".</center>
</body>

</html>
