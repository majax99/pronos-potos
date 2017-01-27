
<?php

//include('.././includes/fonctions.php');
        
        
 //echo "<h2>Connectés</h2>";
$sql=$bdd->prepare("SELECT membre_pseudo FROM membres_online
LEFT JOIN membres ON membre_id = online_id");
$sql->execute();
while($r=$sql->fetch()){
 echo "{$r['membre_pseudo']}<br>";
}       
        
        
        
        
        
        
        
        
?>      
