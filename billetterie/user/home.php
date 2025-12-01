<?php
session_start();
include '../config.php';

// Vérifie que l'utilisateur est connecté
if(!isset($_SESSION['user']) || $_SESSION['role']!='user'){
    header('Location: ../login.php');
    exit;
}

// Initialiser le panier si inexistant
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Ajouter un billet au panier
if(isset($_POST['add_cart'])){
    $ticket_id = $_POST['ticket_id'];
    $quantity = (int)$_POST['quantity'];

    if(isset($_SESSION['cart'][$ticket_id])){
        $_SESSION['cart'][$ticket_id] += $quantity;
    } else {
        $_SESSION['cart'][$ticket_id] = $quantity;
    }

    $message = "Billet ajouté au panier !";
}

// Récupérer tous les billets disponibles
$tickets = $conn->query("SELECT * FROM tickets WHERE stock > 0");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil Utilisateur</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Bienvenue <?= htmlspecialchars($_SESSION['user']) ?> !</h2>

<p style="text-align:center;">
    <a href="../logout.php">Se déconnecter</a> | 
    <a href="cart.php">Voir le panier (<?= count($_SESSION['cart']) ?>)</a>
</p>

<?php if(isset($message)) echo "<p style='color:green;text-align:center;'>$message</p>"; ?>

<?php
// Afficher le bouton seulement si le compte peut être admin
$stmt = $conn->prepare("SELECT role FROM users WHERE username=?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if($row['role'] == 'admin'): ?>
    <p style="text-align:center; margin-top:15px;">
        <a href="../login.php?admin=1" 
           style="background:#4e54c8;padding:8px 12px;color:white;border-radius:5px;text-decoration:none;">
           Se connecter en tant qu'Admin
        </a>
    </p>
<?php endif; ?>

<table>
<tr>
    <th>Événement</th>
    <th>Date</th>
    <th>Prix</th>
    <th>Stock</th>
    <th>Ajouter au panier</th>
</tr>
<?php while($row=$tickets->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['event_name']) ?></td>
    <td><?= $row['event_date'] ?></td>
    <td><?= $row['price'] ?> €</td>
    <td><?= $row['stock'] ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock'] ?>">
            <input type="submit" name="add_cart" value="Ajouter">
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
