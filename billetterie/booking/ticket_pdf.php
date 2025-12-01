<?php
require('../fpdf/fpdf.php');
include('../phpqrcode/qrlib.php');
include '../config.php';
session_start();

$user = $_SESSION['user'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s",$user);
$stmt->execute();
$user_id = $stmt->get_result()->fetch_assoc()['id'];


$result = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 5");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Tickets Billetterie',0,1,'C');

while($row=$result->fetch_assoc()){
    $event = $conn->query("SELECT * FROM events WHERE id=".$row['event_id'])->fetch_assoc();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Evenement: '.$event['title'],0,1);
    $pdf->Cell(0,8,'Date: '.$event['event_date'],0,1);
    $pdf->Cell(0,8,'Sièges: '.$row['seats'],0,1);
    $pdf->Cell(0,8,'Total: '.$row['total'].'€',0,1);

    
    $qrFile = tempnam(sys_get_temp_dir(), 'qrcode').'.png';
    QRcode::png('OrderID:'.$row['id'].' User:'.$user, $qrFile, 'L',4,2);
    $pdf->Image($qrFile,null,null,30,30);
    unlink($qrFile);

    $pdf->Ln(15);
}

$pdf->Output();
?>
