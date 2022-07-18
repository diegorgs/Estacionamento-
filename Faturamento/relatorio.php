<?php 
session_start();
if(isset($_SESSION["criterio"])){
    $where = $_SESSION["criterio"];
}else{
    $where= '';
}
// $where = (isset($_SESSION["criterio"]))? $where = $_SESSION["criterio"]:$where='';
$filtro = "";
if (isset($_SESSION["filtro"])) {
    $filtro = $_SESSION["filtro"];
}

require('fpdf.php');

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->SetTitle("Estacionamento",true);
$pdf->AddPage('P', 'a4', 0, 1);

$pdf->SetFont('arial','',16);
$pdf->SetTextColor(50,50,50);
$posY = $pdf->GetY();
$pdf->Cell(170,10,utf8_decode('Relatório Diario'), 1, 0, 'C');
$pdf->SetFont('arial','',8);
$pdf->Cell(20,10, '',1,1);

$pdf->image('img/logoTipo.png',10.5,$posY + 1,0,9);

$pdf->SetXY(180,11);
$pdf->Cell(20,7,date('d/m/Y'),0,1,'C');
$pdf->SetFont('arial','',8);
$pdf->SetXY(180,16);
$pdf->Cell(20,3,date('H/i'),0,1,'C');

$pdf->SetY($posY + 10);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(190, 10, $filtro, 1, 1, 'C');

$pdf->SetFont('arial','',12);
$pdf->SetTextColor(50,50,50);
$pdf->Cell(10, 8 ,"#", 1, 0,'C',);
$pdf->Cell(60, 8 , "Entrada", 1, 0, 'C', );
$pdf->Cell(60, 8 , "Saida",1,0,'C',);
$pdf->Cell(30, 8 , "Placa",1,0,'C',);
$pdf->Cell(30, 8 , "Valor Pago",1,1,'C',);



$localServidor = "localhost";
$user = "root";
$senha = "";
$banco = "estacionamento";

$conn = mysqli_connect($localServidor,$user,$senha,$banco);

$sql = "SELECT *
        FROM estacionamentodiario $where";
$result = (mysqli_query($conn, $sql));
$i = 1;
while ($linha = mysqli_fetch_array($result)){
    $pdf->cell(10, 8, $i , 'B', 0 , 'C');
    $pdf->cell(60, 8,$linha["dEntrada"],'B',0,'C');
    $pdf->cell(60, 8,$linha["dSaida"],'B',0,'C');
    $pdf->cell(30, 8,$linha["placa"],'B',0,'C');
    $pdf->cell(30, 8,$linha["valortotal"],'B',1,'C');
    $i += 1;

}





$pdf->Output('', 'RelatorioEstacionamento.pdf');