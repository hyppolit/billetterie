<?php
include '../config.php';
$events = $conn->query("SELECT * FROM events ORDER BY event_date");
?>
<!DOCTYPE html>
<html>
<head>
<title>Événements</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div style="width:800px; margin:auto; padding-top:20px;">
<h2>Événements disponibles</h2>
<table>
<tr><th>Titre</th><th>Date</th><th>Lieu</th><th>Prix</th><th>Action</th></tr>
<?php while($row=$events->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['event_date']; ?></td>
<td><?php echo $row['venue']; ?></td>
<td><?php echo $row['price']; ?></td>
<td><a href="seats_public.php?id=<?php echo $row['id']; ?>">Réserver</a></td>
</tr>
<?php } ?>
</table>
<a href="../login.php">Se connecter / Créer un compte</a>
</div>
</body>
</html>
