<?php
include '../config.php';
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');
$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
<title>Panier</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div style="width: 600px; margin: auto; padding-top: 20px;">
<h2>Panier</h2>
<?php foreach($cart as $event_id=>$seats){
$e = $conn->query("SELECT * FROM events WHERE id=$event_id")->fetch_assoc();
echo "<b>Event:</b> {$e['title']}<br>";
echo "<b>Sièges:</b> ".implode('; ',$seats)."<br><br>";
} ?>
<a href="checkout.php">Passer au paiement / générer ticket</a><br>
<a href="../dashboard.php">Retour Dashboard</a>
</div>
</body>
</html>