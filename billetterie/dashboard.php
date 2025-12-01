<?php
session_start();
include '../config.php';

if(!isset($_SESSION['user']) || $_SESSION['role']!='admin'){
    header('Location: ../login.php');
    exit;
}

// Bouton switch vers utilisateur
if(isset($_GET['switch_user'])){
    $_SESSION['role']='user';
    header('Location: ../user/home.php');
    exit;
}

// Récupérer les billets pour l’admin
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
    <a href="?switch_user=1">Se connecter en tant qu'utilisateur</a> | 
    <a href="../logout.php">Se déconnecter</a>
</p>

<table>
<tr>
    <th>Événement</th>
    <th>Date</th>
    <th>Prix</th>
    <th>Stock</th>
</tr>
<?php while($row=$tickets->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['event_name']) ?></td>
    <td><?= $row['event_date'] ?></td>
    <td><?= $row['price'] ?> €</td>
    <td><?= $row['stock'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
