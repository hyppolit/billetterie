<?php
include '../config.php';
session_start();
$event_id = $_POST['event_id'];
$selected_seats = $_POST['seats'] ?? [];



if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if(!isset($_SESSION['cart'][$event_id])) $_SESSION['cart'][$event_id] = [];



$_SESSION['cart'][$event_id] = array_merge($_SESSION['cart'][$event_id], $selected_seats);



header('Location: cart_public.php');
exit;

?>
