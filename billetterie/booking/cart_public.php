<?php
include '../config.php';
session_start();
$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
<title>Panier</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div style="width:600px; margin:auto; padding-top:20px;">
<h2>Panier</h2>
<?php if(empty($cart)){ echo '<p>Votre panier est vide.</p>'; } else {
foreach($cart as $event_id=>$seats){
$e = $conn->query("SELECT * FROM events WHERE id=$event_id")->fetch_assoc();
echo "<b>Événement:</b> {$e['title']}<br>";
echo "<b>Sièges:</b> ".implode('; ',$seats)."<br>";
echo "<b>Total:</b> ".($e['price']*count($seats))."€<br><br>";
}
}
?>
<a href="checkout_public.php">Passer au paiement / générer ticket</a><br>
<a href="../events/list_public.php">Retour événements</a>
</div>
</body>
</html>