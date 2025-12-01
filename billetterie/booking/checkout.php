<?php
include '../config.php';
session_start();
$user = $_SESSION['user'];
$cart = $_SESSION['cart'] ?? [];
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$user_id = $stmt->get_result()->fetch_assoc()['id'];


foreach($cart as $event_id => $seats){
$total = 0;
$event = $conn->query("SELECT price FROM events WHERE id=$event_id")->fetch_assoc();
$total = $event['price'] * count($seats);
$stmt = $conn->prepare("INSERT INTO orders(user_id,event_id,seats,total) VALUES(?,?,?,?)");
$seat_csv = implode(';',$seats);
$stmt->bind_param("iisd", $user_id, $event_id, $seat_csv, $total);
$stmt->execute();

foreach($seats as $s){
list($r,$c) = explode(',',$s);
$conn->query("UPDATE seats SET status='sold' WHERE event_id=$event_id AND row_num=$r AND col_num=$c");
}
}
unset($_SESSION['cart']);
header('Location: ticket_pdf.php');
exit;
?>