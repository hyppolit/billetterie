<?php
include '../config.php';
session_start();
$event_id = $_POST['event_id'];
$selected_seats = $_POST['seats'] ?? [];


// Créer panier en session si pas existant
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if(!isset($_SESSION['cart'][$event_id])) $_SESSION['cart'][$event_id] = [];


// Ajouter sièges sélectionnés
$_SESSION['cart'][$event_id] = array_merge($_SESSION['cart'][$event_id], $selected_seats);


// Redirection vers panier
header('Location: cart_public.php');
exit;
?>