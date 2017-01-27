<?php

//get the q parameter from URL



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();

$q=$_GET["q"];

$getrss_query = $bdd->prepare('SELECT *  FROM club_l1 WHERE club_name = :club ');    
$getrss_query->execute(array('club' => $_SESSION['membre_club'] ));

while ($getrss_query2 = $getrss_query->fetch())
            {   
$dateRSS = $getrss_query2['club_formatDateRSS'];
            }


 $date= ($q == "Ligue1")? 'pubDate': $dateRSS  ;    
 



//find out which feed was selected

if($q=="Ligue1") {

  $xml=("http://www.lfp.fr/ligue1/rss.xml");

} else {

  $xml=($q);

}



$xmlDoc = new DOMDocument();

$xmlDoc->load($xml);



//get elements from "<channel>"

$channel=$xmlDoc->getElementsByTagName('channel')->item(0);

$channel_title = $channel->getElementsByTagName('title')

->item(0)->childNodes->item(0)->nodeValue;

$channel_link = $channel->getElementsByTagName('link')

->item(0)->childNodes->item(0)->nodeValue;

$channel_desc = $channel->getElementsByTagName('description')

->item(0)->childNodes->item(0)->nodeValue;



//output elements from "<channel>"

echo("<p><a href='" . $channel_link

  . "'>" . $channel_title . "</a>");

echo("<br>");

echo($channel_desc . "</p>");



//get and output "<item>" elements



$x=$xmlDoc->getElementsByTagName('item');

for ($i=0; $i<=5; $i++) {


  $item_date=$x->item($i)->getElementsByTagName($date)

  ->item(0)->childNodes->item(0)->nodeValue;

echo "<span class='label label-success'>".date("d/m/Y H:m:s", strtotime($item_date))."</span >" ;


  $item_title=$x->item($i)->getElementsByTagName('title')

  ->item(0)->childNodes->item(0)->nodeValue;

  $item_link=$x->item($i)->getElementsByTagName('link')

  ->item(0)->childNodes->item(0)->nodeValue;

  $item_desc=$x->item($i)->getElementsByTagName('description')

  ->item(0)->childNodes->item(0)->nodeValue;



  echo ("<p><a href='" . $item_link

  . "'>" . $item_title . "</a>");

  echo ("<br>");

  echo ($item_desc . "</p>");

}

?>