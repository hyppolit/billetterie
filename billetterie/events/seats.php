<?php
include '../config.php';
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');


$event_id = $_GET['id'];
$event = $conn->query("SELECT * FROM events WHERE id=$event_id")->fetch_assoc();
$seats = $conn->query("SELECT * FROM seats WHERE event_id=$event_id");


$seatMap = [];
while($s=$seats->fetch_assoc()) {
$seatMap[$s['row_num']][$s['col_num']] = $s['status'];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sélection des sièges</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div style="width: 600px; margin: auto; padding-top: 20px;">
<h2>Événement: <?php echo $event['title']; ?></h2>
<form method="post" action="../booking/add_to_cart.php">
<table>
<?php for($i=1;$i<=$event['rows'];$i++){
echo '<tr>';
for($j=1;$j<=$event['cols'];$j++){
$status = $seatMap[$i][$j] ?? 'free';
$disabled = ($status!='free')?'disabled':'';
echo "<td><input type='checkbox' name='seats[]' value='$i,$j' $disabled>$i,$j ($status)</td>";
}
echo '</tr>';
} ?>
</table>
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="submit" value="Ajouter au panier">
</form>
<a href="list.php">Retour événements</a>
</div>
</body>
</html>