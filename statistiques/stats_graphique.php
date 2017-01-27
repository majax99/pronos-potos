

<?php

/*

Règlement du site (pronostics, classement,...)

*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();



/* Si le membre n'est pas connecté, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}

/********Fin actualisation de session...**********/



/********Entête et titre de page*********/



$titre = 'Graphiques';



$id = $_GET['id'];

/**********Fin entête et titre***********/

?>



    

<div id="container" style="height:500px;"></div>

    

<br><br>

    

<div id="container2" style="height:600px;" ></div>

    

<br><br>

    

<div id="container3" style="height:600px;"></div>



<br><br>

    

<div id="container4" ></div>

 

<br><br>

<div id="container5" ></div>

 

<br><br>



<?php



    /* on récupère la dernière journée où un score a été indiqué */

  $date_prochainMatch = $bdd->query('SELECT matchl1_journee FROM (select max(matchl1_journee) as matchl1_journee  from match_ligue1 where matchl1_resultat <> "" order by matchl1_date DESC LIMIT 1) l ');

 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);

$journeeEnCours = $date_prochainMatch['matchl1_journee'] ;        

    

/****************************************************************************/   

// Requête pour récupérer le classement journée pour chaque journée et le nombre de points obtenus

/****************************************************************************/   

    

        $class_journee = $bdd->prepare('SELECT class_membre_id , class_pts_journee,class_rang_journee, class_journee FROM classement_journee WHERE class_membre_id = :id ');

        $class_journee->execute(array('id' => $id));

        $i=1;

        

                while ($class_journee2 = $class_journee->fetch())

            {

                if (!empty($class_journee)) {    

                $journee=$class_journee2['class_journee'];

                $point=$class_journee2['class_pts_journee'];

                $rang_journee=$class_journee2['class_rang_journee'];

                

                 while ($class_journee2['class_journee'] != $i)

                {

                    if ($i==1) {$val=array(0);$axeX=array($i);$rang=array(0);$i++;}

                     else {$val[]=0;$axeX[]=$i;$rang[]=0;$i++;}

                     

                }

                  if ($journee==1) {$val=array($point);$axeX=array($i);$rang=array($rang_journee);$i++;}   

                  else {$val[]=$point;$axeX[]=$i;$rang[]=$rang_journee;$i++;}

                        

                                                }

                

            }

        

    if (!isset($val)) {$val=array(0);$axeX=array($i);$rang=array(0);}

    

    $journeeValues = implode(",", $val);       

    $axeX2=implode(",", $axeX);

    $rang2=implode(",", $rang);

    

    

/****************************************************************************/    

// Requête pour récupérer l'évolution du classement général pour chaque personne

/****************************************************************************/   

$cumulPts_journee = $bdd->query('

SELECT classgen_membre_pseudo , classgen_ptscumul, classgen_ptsjournee, classgen_journee

FROM classementgeneral_journee 

WHERE classgen_journee <= '.$journeeEnCours .'

ORDER BY classgen_journee , classgen_ptscumul DESC');

        $i=1;

        $journee2=1;

        $rang=0;

        $point=0;

        $tab_pseudo = array();

        $tab_journee= array();

        $tab_rang= array();

                while ($cumulPts_journee2 = $cumulPts_journee->fetch())

            {

                

                $journee=$cumulPts_journee2['classgen_journee'];

                $pts_cumul=$cumulPts_journee2['classgen_ptscumul'];

                $pseudo = $cumulPts_journee2['classgen_membre_pseudo'];

                $pts_journee = $cumulPts_journee2['classgen_ptsjournee'];

                //$journee=$cumulPts_journee2['matchl1_journee'];



 if ($journee2 < $journee) {$i=1;$rang=0;$journee2++;}                        

                    

 if ($pts_cumul != $point) { $rang=$i; } //en cas d'égalité entre 2 personnes, elles ont le même rang

 //$classG_j[$pseudo][$journee]=$rang;   // on enregistre les données dans un tableau pour créer le classement

 //$classG_j[$pseudo][$journee]['point']=$pts_cumul;

 $i++;

 $point=$pts_cumul;

                    

$tab_journee[] =  $journee;

$tab_pseudo[] = '"'.$pseudo.'"'; // on met des quotes pour les caractères

$tab_rang[] =  $rang; // on enregistre les données dans un tableau pour créer le classement

            }



$array_pseudo=   implode(",", $tab_pseudo);  

$array_journee=   implode(",", $tab_journee);  

$array_rang=   implode(",", $tab_rang); 





// On souhaite récupérer le pseudo suite à la modification du Select

$membre_pseudo_query = $bdd->prepare('SELECT membre_pseudo  FROM membres WHERE membre_id = :id LIMIT 1');

$membre_pseudo_query->execute(array('id' => $id));

$membre_pseudo_query = $membre_pseudo_query->fetch(PDO::FETCH_ASSOC);

$pseudoDuMembre = $membre_pseudo_query['membre_pseudo'];




/************************************************************************************/    
// Requ�te pour r�cup�rer l'�volution du nombre de points cumul�s pour chaque personne
/************************************************************************************/   

$cumulPts_journee = $bdd->query('
SELECT classgen_membre_pseudo , classgen_ptscumul, classgen_ptsjournee, classgen_journee
FROM classementgeneral_journee 
WHERE classgen_journee <= '.$journeeEnCours .'
ORDER BY classgen_journee , classgen_ptscumul DESC');
        $i=1;
        $journee2=1;
        $rang=0;
        $point=0;
        $tab_pseudo = array();
        $tab_journee= array();
        $tab_pts= array();
                while ($cumulPts_journee2 = $cumulPts_journee->fetch())
            {
                
                $journee=$cumulPts_journee2['classgen_journee'];
                $pts_cumul=$cumulPts_journee2['classgen_ptscumul'];
                $pseudo = $cumulPts_journee2['classgen_membre_pseudo'];
                //$journee=$cumulPts_journee2['matchl1_journee'];

 if ($journee2 < $journee) {$i=1;$journee2++;}                        
 $i++;
                    
$tab_journee_pts[] =  $journee;
$tab_pseudo_pts[] = '"'.$pseudo.'"'; // on met des quotes pour les caract�res
$tab_pts[] =  $pts_cumul; // on enregistre les donn�es dans un tableau pour cr�er le classement
            }

$array_pseudo_pts=   implode(",", $tab_pseudo);  
$array_journee_pts=   implode(",", $tab_journee);  
$array_pts=   implode(",", $tab_pts); 


// On souhaite r�cup�rer le pseudo suite � la modification du Select
$membre_pseudo_query = $bdd->prepare('SELECT membre_pseudo  FROM membres WHERE membre_id = :id LIMIT 1');
$membre_pseudo_query->execute(array('id' => $id));
$membre_pseudo_query = $membre_pseudo_query->fetch(PDO::FETCH_ASSOC);
$pseudoDuMembre = $membre_pseudo_query['membre_pseudo'];
    

/****************************************************************************/      

// Requête pour récupérer le nombre de résultats trouvés par journée

/****************************************************************************/   

    

unset($val);

unset($axeX);   

        $resultat_par_journee = $bdd->prepare('SELECT membre_id , matchl1_journee, case when trouve IS NULL then 0 

 ELSE trouve END AS trouve 

 FROM 

(SELECT membre_id, A.matchl1_journee, sum(resultat_trouve) as trouve

FROM v_membres_journee A LEFT JOIN`v_classement_L1` B

ON membre_id = pronol1_membre_id AND A.matchl1_journee = B.matchl1_journee

WHERE membre_id = :id

group by A.matchl1_journee ) T');

        $resultat_par_journee->execute(array('id' => $id));

        $i=1;

                while ($resultat_par_journee2 = $resultat_par_journee->fetch())

            {

                $journee=$resultat_par_journee2['matchl1_journee'];

                $resTrouve=$resultat_par_journee2['trouve'];

                     

        

                  if ($journee==1) {$val=array($resTrouve);$axeX=array($journee);$i++;}   

                  else {$val[]=$resTrouve;$axeX[]=$journee;$i++;}

            }

        

    $journeeres = implode(",", $val);       

    $axeXres=implode(",", $axeX);

        

    

/****************************************************************************/     

// Requête pour l'évolution des bonus gagnés par journée

/****************************************************************************/   

    

unset($val);

unset($axeX);   



        $bonus_par_journee = $bdd->prepare('SELECT membre_id , A.matchl1_journee  , 

CASE WHEN points_bonus IS NULL or points_bonus = 0 then 40 

 ELSE points_bonus END AS points_bonus ,

CASE WHEN matchl1_name IS NULL then "bonus non joué"

 ELSE matchl1_name END AS matchl1_name ,

 CASE WHEN pronol1_resultat IS NULL then "" 

 ELSE pronol1_resultat END AS pronol1_resultat 

FROM v_membres_journee A LEFT JOIN 

(SELECT pronol1_membre_id , matchl1_journee, matchl1_name ,pronol1_resultat, points_bonus

FROM `v_classement_L1` 

WHERE pronol1_membre_id = :id AND pronol1_bonus = 5

 ) T 

ON membre_id = pronol1_membre_id AND A.matchl1_journee = T.matchl1_journee

WHERE membre_id = :id 

order by matchl1_journee ');

        $bonus_par_journee->execute(array('id' => $id));

        $i=1;

                while ($bonus_par_journee2 = $bonus_par_journee->fetch())

            {

                $journee=$bonus_par_journee2['matchl1_journee'];

                $ptsbonus=$bonus_par_journee2['points_bonus'];

                $match=$bonus_par_journee2['matchl1_name'];

                $prono=$bonus_par_journee2['pronol1_resultat'];

                $colorGraph = ($ptsbonus == 40)? "#C5C8C9" : "#36BF46"; //B4B7B8

                    

                  if ($journee==1) 

                  {

                       $val=array($ptsbonus);$axeX=array($journee);$tabmatch=array($match);$tabprono=array($prono);

                      

                      $tabcolor = array($colorGraph);

                      $i++;

                  }   

                  else {$val[]=$ptsbonus;$axeX[]=$journee;$tabmatch[] =$match;$tabprono[] =$prono;$tabcolor[] =$colorGraph; $i++;}

            }

        

    $journeebonus = implode(",", $val);       

    $axeXbonus=implode(",", $axeX);

    //$arraymatch = implode(",", $tabmatch);       

    //$arrayprono=implode(",", $tabprono);

  //  print_r($colortab);

      ?>     



    <script> 





// GRAPHIQUE AVEC LES POINTS ET CLASSEMENT PAR JOURNEE         

$(function () { 

    var myChart = Highcharts.chart('container', {

    title: {

            text: 'Evolution du classement et du nombre de points par journée'

        },

    credits: {

      enabled: false

            },

        tooltip: {

            headerFormat: '<b>journée {point.x}</b><br>',

            shared: true,

        },

    xAxis: {

            categories: [<?php echo $axeX2 ?>],

            title: {

             text: 'Journée'

                   }

        },

    yAxis: [{ //--- Primary yAxis

    title: {

        text: 'Classement'

    },      tickInterval: 3,

            min : 1,
            reversed: true



}, { //--- Secondary yAxis

    title: {

        text: 'Points'

    },

    labels: {

      format: '{value} pts'

            },

    opposite: true

}],

    series: [{

    yAxis: 0,

    name: 'Classement',

    data: [<?php echo $rang2 ?>],

    color : '#32C784',

    zIndex: 2 

},

            

             {

    yAxis: 1,

    data: [<?php echo $journeeValues ?>],

    name: 'Points',

    type: 'column',

    color : '#4070D6',

    zIndex: 1

}]

    });
});    

        

        

// GRAPHIQUE AVEC LE CLASSEMENT GENERAL PAR JOURNEE POUR CHAQUE MEMBRE            

$(function () {   

    

    var journeeNoArray = [<?php echo $array_journee; ?>],

    pseudoNameArray = [<?php echo $array_pseudo; ?>],

    rangArray = [<?php echo $array_rang; ?>],

    series = [];



    var id = '<?php echo $pseudoDuMembre; ?>';

    series = generateData(journeeNoArray, pseudoNameArray, rangArray);





    function generateData(cats, names, points) {

        var ret = {},

            ps = [],

            series = [],

            len = cats.length;



        //concat to get points

        for (var i = 0; i < len; i++) {

            ps[i] = {

                x: cats[i],

                y: points[i],

                n: names[i]

            };

        }

        names = [];

        

        //generate series and split points

        for (i = 0; i < len; i++) {

            var p = ps[i],

                sIndex = $.inArray(p.n, names);



            if (sIndex < 0) {

                sIndex = names.push(p.n) - 1;

                

                if (id==p.n) {//Si le pseudo est le mien, j'affiche la personne sur le graphe, sinon je ne l'affiche pas

                

                series.push({

                    name: p.n,

                    data: [],

                    visible:true

                });

                      }         

                else {

                 series.push({

                 name: p.n,

                 data: [],

                 visible:false

                     

                })

                    };

            }            

            series[sIndex].data.push(p);

        }

        return series;



    }





    var myChart = Highcharts.chart('container2', {

        title: {

            text: 'Evolution du classement général par journée',

            x: -20 //center

        },

        subtitle: {

            text: 'Cliquer sur les pseudos à droite pour faire apparaître leur courbe',

            x: -20 //center

        },

    yAxis: [{ 

    title: {

        text: 'Classement'

    },      tickInterval: 3,

            reversed: true,

            min : 1

}],

    credits: {

      enabled: false

            },

        tooltip: {

            headerFormat: '<b>journée {point.x}</b><br>'

        },

    xAxis: {

            title: {

             text: 'Journée'

                   }

        },

        legend: {

            layout: 'vertical',

            align: 'right',

            verticalAlign: 'middle',

            borderWidth: 0

        },

        series: series

    });

});

        
// GRAPHIQUE AVEC LE NOMBRE DE POINTS CUMULE PAR JOURNEE POUR CHAQUE MEMBRE            
$(function () {   
    
    var journeeNoArray = [<?php echo $array_journee; ?>],
    pseudoNameArray = [<?php echo $array_pseudo; ?>],
    ptsArray = [<?php echo $array_pts; ?>],
    series = [];

    var id = '<?php echo $pseudoDuMembre; ?>';
    series = generateData(journeeNoArray, pseudoNameArray, ptsArray);


    function generateData(cats, names, points) {
        var ret = {},
            ps = [],
            series = [],
            len = cats.length;

        //concat to get points
        for (var i = 0; i < len; i++) {
            ps[i] = {
                x: cats[i],
                y: points[i],
                n: names[i]
            };
        }
        names = [];
        
        //generate series and split points
        for (i = 0; i < len; i++) {
            var p = ps[i],
                sIndex = $.inArray(p.n, names);

            if (sIndex < 0) {
                sIndex = names.push(p.n) - 1;
                
                if (id==p.n) {//Si le pseudo est le mien, j'affiche la personne sur le graphe, sinon je ne l'affiche pas
                
                series.push({
                    name: p.n,
                    data: [],
                    visible:true
                });
                      }         
                else {
                 series.push({
                 name: p.n,
                 data: [],
                 visible:false
                     
                })
                    };
            }            
            series[sIndex].data.push(p);
        }
        return series;

    }


    var myChart = Highcharts.chart('container3', {
        title: {
            text: 'Evolution du nombre de points cumulés par journée',
            x: -20 //center
        },
        subtitle: {
            text: 'Cliquer sur les pseudos à droite pour faire apparaître leur courbe',
            x: -20 //center
        },
    yAxis: [{ 
    title: {
        text: 'points cumulés'
    },      tickInterval: 3,
            min : 1
}],
    credits: {
      enabled: false
            },
        tooltip: {
            headerFormat: '<b>journée {point.x}</b><br>'
        },
    xAxis: {
            title: {
             text: 'Journée'
                   }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: series
    });
});   
        

// GRAPHIQUE AVEC LE NOMBRE DE RESULTATS TROUVES PAR JOURNEE      

$(function () { 

    var myChart = Highcharts.chart('container4', {

    title: {

            text: 'Nombre de résultats trouvés par journée'

        },

    chart: {

        type: 'column'

        },

    credits: {

      enabled: false

            },

    tooltip: {

            headerFormat: '<b>journée {point.x}</b><br>'

        },

    xAxis: {

            categories: [<?php echo $axeXres ?>],

            title: {

             text: 'Journée'

                   }

        },

    yAxis: [{ 

    title: {

        text: 'Résultats trouvés'

    },      tickInterval: 1,

}],

    series: [{

    data: [<?php echo $journeeres ?>],

    name: 'résultats',

    color : '#4070D6'

}]

    });

});         

        

// GRAPHIQUE AVEC LES POINTS DES BONUS PAR JOURNEE

$(function () { 

    

    var matchArray = [<?php echo '"'.implode('","', $tabmatch).'"' ?>];

    var pronoArray = [<?php echo '"'.implode('","', $tabprono).'"' ?>];

    var journeeArray = [<?php echo '"'.implode('","', $axeX).'"' ?>];

    var colorArray = [<?php echo '"'.implode('","', $tabcolor).'"' ?>];

    

    var myChart = Highcharts.chart('container5', {

    title: {

            text: 'Nombre de points bonus par journée',

        },

    subtitle : { text: 'les zones grisés sont les journées sans bonus'},

    chart: {

        type: 'column'

        },

        

                plotOptions: {

                column: {

                    colorByPoint: true

                }

            },

            colors: colorArray,    

        

    credits: {

      enabled: false

            },

    tooltip: {

          /*  headerFormat: '<b>journée {point.x}</b><br>',

            pointFormat: '{series.name}: <strong>{point.y}<strong><br>'*/

           formatter: function () {

        var result = '<b> Journée ' + this.x + '</b>';

        var result = result + '<br/><b>Points bonus : </b>' +this.y;

        var index = journeeArray.indexOf(this.x);

        var match = matchArray[index];

        var prono = pronoArray[index];

        var result = result +  '<br/> '+ match + ' : ' + prono  ;

        //return 'journee'+this.x '<br>'+comment;

               return result;

    }



            

        },

    xAxis: {

            categories: journeeArray,

            title: {

             text: 'Journée'

                   }

        },

    yAxis: [{ 

    title: {

        text: 'points bonus'

    }

}],

   legend : {  enabled : false

            } , 

        

    series: [{

    data: [<?php echo $journeebonus ?>],

    name: 'points bonus'

}] 

    });

});  

    </script>





    <!-- END # MODAL LOGIN -->



