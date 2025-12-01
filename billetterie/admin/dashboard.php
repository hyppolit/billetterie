<?php
session_start();
include '../config.php';


if(!isset($_SESSION['user']) || $_SESSION['role']!='admin'){
    header('Location: ../login.php');
    exit;
}


$tickets = $conn->query("SELECT * FROM tickets");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Bienvenue Admin <?= htmlspecialchars($_SESSION['user']) ?> !</h2>
<p style="text-align:center;">
    <a href="../logout.php">Se déconnecter</a>
</p>

<h3 style="text-align:center;">Liste des billets</h3>
<table>
<tr>
    <th>ID</th>
    <th>Événement</th>
    <th>Date</th>
    <th>Prix</th>
    <th>Stock</th>
</tr>
<?php while($row = $tickets->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['event_name']) ?></td>
    <td><?= $row['event_date'] ?></td>
    <td><?= $row['price'] ?> €</td>
    <td><?= $row['stock'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
<p style="text-align:center; margin-bottom:20px;">
    <a href="manage_tickets.php" 
       style="background:#4e54c8;padding:10px 15px;color:white;border-radius:5px;text-decoration:none;">
       Gérer les billets
    </a>
</p>


