
<?php

if(isset($_SESSION['membre_id'])) {

//Création des variables
$ip = ip2long($_SERVER['REMOTE_ADDR']);
$id = $_SESSION['membre_id'];
$time = time();
//Requête
$query=$bdd->prepare('INSERT INTO membres_online VALUES(:id, :time,:ip)
ON DUPLICATE KEY UPDATE
online_time = :time , online_id = :id');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->bindValue(':time',$time, PDO::PARAM_INT);
$query->bindValue(':ip', $ip, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();




$time_max = time() - (60 * 5);
$query=$bdd->prepare('DELETE FROM membres_online WHERE online_time < :timemax');
$query->bindValue(':timemax',$time_max, PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();

}

?>            