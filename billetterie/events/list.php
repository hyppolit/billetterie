<?php
include '../config.php';
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');


$result = $conn->query("SELECT * FROM events ORDER BY event_date");
?>
<!DOCTYPE html>
<html>
<head>
<title>Liste des événements</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div style="width: 800px; margin: auto; padding-top: 20px;">
<h2>Événements</h2>
<table>
<tr><th>ID</th><th>Titre</th><th>Date</th><th>Lieu</th><th>Prix</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['event_date']; ?></td>
<td><?php echo $row['venue']; ?></td>
<td><?php echo $row['price']; ?></td>
<td><a href="seats.php?id=<?php echo $row['id']; ?>">Sélection sièges</a></td>
</tr>
<?php } ?>
</table>
<a href="../dashboard.php">Retour Dashboard</a>
</div>
</body>
</html>