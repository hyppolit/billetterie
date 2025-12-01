<?php
include 'config.php';
session_start();

$error = '';
$isAdminLogin = isset($_GET['admin']) && $_GET['admin']==1;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows==0){
        $error = "Nom d'utilisateur incorrect !";
    } else {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            if($isAdminLogin && $row['role']!='admin'){
                $error = "Accès interdit, vous n'êtes pas admin !";
            } else {
                $_SESSION['user']=$username;
                $_SESSION['role']=$row['role'];

                if($row['role']=='admin'){
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: user/home.php');
                }
                exit;
            }
        } else {
            $error = "Mot de passe incorrect !";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div style="width:300px;margin:auto;padding-top:50px;">
    <h2>Connexion</h2>
    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <input type="submit" value="Se connecter">
    </form>
    <p style="text-align:center;"><a href="register.php">Créer un compte</a></p>
</div>
</body>
</html>
