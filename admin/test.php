<form method="post">
<input type="checkbox" name="check1" />
<input type="submit" value="valider1" />
</form>
 
<form method="post">
<input type="checkbox" name="check2"/>
<input type="submit" value="valider2" />
</form>
 
<?php
if(isset($_POST['check2'])){
    echo $_POST['check2'];
}else if(isset($_POST['check1'])){
    echo $_POST['check1'];
}
?>