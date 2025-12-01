<?php
include 'config.php';
header('Location: events/list_public.php');
exit;
?>
<section class="hero">
    <h1>Bienvenue sur la billetterie officielle</h1>
    <p>Achetez vos billets pour tous les évènements en un clic.</p>
    <a href="events/list_public.php" class="btn-primary">Voir les évènements</a>
    <a href="login.php?admin=1" class="btn-primary" style="background-color:#ff4757;">Espace Admin</a>
</section>