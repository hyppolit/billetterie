<?php
session_start();
include '../config.php';

if(!isset($_SESSION['user']) || $_SESSION['role']!='user'){
    header('Location: ../login.php');
    exit;
}

// Initialiser le panier si inexistant
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Valider l'achat
if(isset($_POST['checkout'])){
    foreach($_SESSION['cart'] as $ticket_id => $quantity){
        // Vérifier le stock
        $stmt = $conn->prepare("SELECT stock FROM tickets WHERE id=?");
        $stmt->bind_param("i",$ticket_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if($res['stock'] >= $quantity){
            // Réduire le stock
            $stmt2 = $conn->prepare("UPDATE tickets SET stock=stock-? WHERE id=?");
            $stmt2->bind_param("ii",$quantity,$ticket_id);
            $stmt2->execute();
        } else {
            $error = "Stock insuffisant pour certains billets.";
        }
    }
    if(!isset($error)){
        $_SESSION['cart'] = [];
        $success = "Achat validé !";
    }
}

// Récupérer les billets dans le panier
$cart_items = [];
if(!empty($_SESSION['cart'])){
    $ids = implode(',', array_keys($_SESSION['cart']));
    $res = $conn->query("SELECT * FROM tickets WHERE id IN ($ids)");
    while($row = $res->fetch_assoc()){
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Votre Panier</h2>
<p style="text-align:center;">
    <a href="home.php">Retour aux billets</a> | 
    <a href="../logout.php">Se déconnecter</a>
</p>

<?php if(isset($success)) echo "<p style='color:green;text-align:center;'>$success</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

<?php if(!empty($cart_items)): ?>
<form method="post">
<table>
<tr>
    <th>Événement</th>
    <th>Date</th>
    <th>Prix</th>
    <th>Quantité</th>
    <th>Total</th>
</tr>
<?php 
$total = 0;
foreach($cart_items as $item): 
    $line_total = $item['price'] * $item['quantity'];
    $total += $line_total;
?>
<tr>
    <td><?= htmlspecialchars($item['event_name']) ?></td>
    <td><?= $item['event_date'] ?></td>
    <td><?= $item['price'] ?> €</td>
    <td><?= $item['quantity'] ?></td>
    <td><?= $line_total ?> €</td>
</tr>
<?php endforeach; ?>
<tr>
    <td colspan="4" style="text-align:right;"><strong>Total :</strong></td>
    <td><strong><?= $total ?> €</strong></td>
</tr>
</table>
<p style="text-align:center; margin-top:15px;">
    <input type="submit" name="checkout" value="Valider l'achat">
</p>
</form>
<?php else: ?>
<p style="text-align:center;">Votre panier est vide.</p>
<?php endif; ?>
</body>
</html>

