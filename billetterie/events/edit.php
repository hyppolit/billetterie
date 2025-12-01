<?php
include '../config.php';
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');



$user = $_SESSION['user'];
$role = $conn->query("SELECT role FROM users WHERE username='$user'")->fetch_assoc()['role'];
if($role != 'admin'){ echo 'Accès interdit'; exit; }


$event_id = $_GET['id'];
$event = $conn->query("SELECT * FROM events WHERE id=$event_id")->fetch_assoc();


if($_SERVER['REQUEST_METHOD']=='POST'){
$title = $_POST['title'];
$date = $_POST['event_date'];
$venue = $_POST['venue'];
$rows = $_POST['rows'];
$cols = $_POST['cols'];
$price = $_POST['price'];


$stmt = $conn->prepare("UPDATE events SET title=?, event_date=?, venue=?, `rows`=?, `cols`=?, price=? WHERE id=?");
$stmt->bind_param("sssiddi", $title,$date,$venue,$rows,$cols,$price,$event_id);
if($stmt->execute()) $success='Événement mis à jour';
else $error='Erreur lors de la mise à jour';
}
?>
<!DOCTYPE html>
<html><head><title>Editer Événement</title></head>
<body>
<h2>Editer Événement</h2>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>
<form method="post">
<input type="text" name="title" value="<?php echo $event['title']; ?>" required><br>
<input type="datetime-local" name="event_date" value="<?php echo date('Y-m-d\TH:i',strtotime($event['event_date'])); ?>" required><br>
<input type="text" name="venue" value="<?php echo $event['venue']; ?>"><br>
<input type="number" name="rows" value="<?php echo $event['rows']; ?>" required><br>
<input type="number" name="cols" value="<?php echo $event['cols']; ?>" required><br>
<input type="number" step="0.01" name="price" value="<?php echo $event['price']; ?>" required><br>
<input type="submit" value="Mettre à jour">
</form>
<a href="list.php">Retour liste événements</a>
</body></html>