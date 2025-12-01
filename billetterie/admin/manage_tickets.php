<?php
session_start();
include '../config.php';


if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header('Location: ../login.php');
    exit;
}


if(isset($_POST['add_ticket'])){
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO tickets (event_name, event_date, price, stock) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $event_name, $event_date, $price, $stock);
    $stmt->execute();
    $message = "Billet ajouté avec succès !";
}


if(isset($_POST['edit_ticket'])){
    $id = $_POST['id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE tickets SET event_name=?, event_date=?, price=?, stock=? WHERE id=?");
    $stmt->bind_param("ssdii", $event_name, $event_date, $price, $stock, $id);
    $stmt->execute();
    $message = "Billet modifié avec succès !";
}


if(isset($_POST['delete_ticket'])){
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tickets WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $message = "Billet supprimé avec succès !";
}


$tickets = $conn->query("SELECT * FROM tickets");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gérer les billets</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Gérer les billets</h2>
<p style="text-align:center;">
    <a href="dashboard.php">Retour au dashboard</a> | 
    <a href="../logout.php">Se déconnecter</a>
</p>

<?php if(isset($message)) echo "<p style='color:green;text-align:center;'>$message</p>"; ?>

<h3>Ajouter un billet</h3>
<form method="post" style="text-align:center;margin-bottom:20px;">
    <input type="text" name="event_name" placeholder="Nom de l'événement" required>
    <input type="datetime-local" name="event_date" required>
    <input type="number" step="0.01" name="price" placeholder="Prix" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <input type="submit" name="add_ticket" value="Ajouter">
</form>

<h3>Liste des billets</h3>
<table>
<tr>
    <th>ID</th>
    <th>Événement</th>
    <th>Date</th>
    <th>Prix</th>
    <th>Stock</th>
    <th>Actions</th>
</tr>
<?php while($row=$tickets->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['event_name']) ?></td>
    <td><?= $row['event_date'] ?></td>
    <td><?= $row['price'] ?> €</td>
    <td><?= $row['stock'] ?></td>
    <td>
        
        <form method="post" style="display:inline-block;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="text" name="event_name" value="<?= htmlspecialchars($row['event_name']) ?>" required>
            <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($row['event_date'])) ?>" required>
            <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required>
            <input type="number" name="stock" value="<?= $row['stock'] ?>" required>
            <input type="submit" name="edit_ticket" value="Modifier" style="background:#4e54c8;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;">
        </form>
      
        <form method="post" style="display:inline-block;">
            <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
            <input type="submit" name="delete_ticket" value="Supprimer" style="background:#ff6b6b;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;">
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>

