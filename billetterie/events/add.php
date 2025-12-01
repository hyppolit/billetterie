<?php
include '../config.php';
session_start();
if(!isset($_SESSION['user'])) header('Location: ../login.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $date = $_POST['event_date'];
    $venue = $_POST['venue'];
    $rows = $_POST['rows'];
    $cols = $_POST['cols'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO events(title,event_date,venue,`rows`,`cols`,price) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("sssidd",$title,$date,$venue,$rows,$cols,$price);
    if($stmt->execute()){
        $event_id = $conn->insert_id;
        
        for($i=1;$i<=$rows;$i++){
            for($j=1;$j<=$cols;$j++){
                $conn->query("INSERT INTO seats(event_id,row_num,col_num,status) VALUES($event_id,$i,$j,'free')");
            }
        }
        $success = "Événement ajouté avec succès!";
    } else {
        $error = "Erreur lors de l'ajout de l'événement.";
    }
}
?>
<!DOCTYPE html>
<html><head><title>Ajouter Événement</title></head>
<body>
<h2>Ajouter Événement</h2>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>
<form method="post">
<input type="text" name="title" placeholder="Titre" required><br>
<input type="datetime-local" name="event_date" required><br>
<input type="text" name="venue" placeholder="Lieu"><br>
<input type="number" name="rows" placeholder="Nombre de rangées" required><br>
<input type="number" name="cols" placeholder="Nombre de colonnes" required><br>
<input type="number" step="0.01" name="price" placeholder="Prix" required><br>
<input type="submit" value="Ajouter">
</form>
<a href="list.php">Retour liste événements</a>
</body></html>
