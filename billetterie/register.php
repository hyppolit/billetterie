<?php
include 'config.php';
if(session_status() == PHP_SESSION_NONE) session_start();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if($username == "" || $password == ""){
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Vérifie si l'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $error = "Cet utilisateur existe déjà.";
        } else {
            // Hash du mot de passe
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $insert->bind_param("ss",$username,$hashed);

            if($insert->execute()){
                $success = "Compte créé avec succès ! <a href='login.php'>Connectez-vous</a>";
            } else {
                $error = "Erreur lors de l'inscription.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <style>
        body {font-family: Arial; background: #f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;}
        .register-box {background:white; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.3); width:350px; text-align:center;}
        .register-box input[type=text], .register-box input[type=password] {width:100%; padding:12px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        .register-box input[type=submit] {width:100%; padding:12px; background:#4e54c8; color:white; border:none; border-radius:5px; cursor:pointer;}
        .register-box input[type=submit]:hover {background:#3b3fc1;}
        .error {color:red; margin-bottom:15px;}
        .success {color:green; margin-bottom:15px;}
        .btn-back {display:inline-block; margin-top:15px; padding:10px 20px; background:#ff6b6b; color:white; text-decoration:none; border-radius:5px;}
        .btn-back:hover {background:#ff4757;}
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Créer un compte</h2>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <?php if($success) echo "<div class='success'>$success</div>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Créer le compte">
        </form>
        <a href="login.php" class="btn-back">Retour à la connexion</a>
    </div>
</body>
</html>


