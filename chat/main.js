
        element = document.getElementById('table_chat'); // mettre le scroll en bas

        element.scrollTop = element.scrollHeight;

$('#envoi').click(function(e){

    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire

    

    var pseudo = $('#pseudo').val() ; 

    var message =  $('#message').val() ;



    // Modification du format de date

    var dAujourdhui = new Date();

    month=dAujourdhui.getMonth() + 1;

    if (month < 10) {var month2 = "0" + month}

    else var month2=month;

    day=dAujourdhui.getDate();

        if (day < 10) {var day2 = "0" + day}

    else var day2=day;

    hrs=dAujourdhui.getHours();

        if (hrs < 10) {var hrs2 = "0" + hrs}

    else var hrs2=hrs;

    min=dAujourdhui.getMinutes();

    if (min < 10) {var min2 = "0" + min}

    else var min2=min;

    var date = day2 + "/" + month2 + " " + hrs2 + "h" + min2 ;

    

    

    if(pseudo != "" && message != ""){ // on vérifie que les variables ne sont pas vides

        $.ajax({

            url : 'chat/traitement.php', // on donne l'URL du fichier de traitement

            type : 'POST',

            data : 'pseudo=' + pseudo + '&message=' + message + '&submit=' + 1,

            // et on envoie nos données



        });



       //$('#messages').append("<p>" + date + "<strong> " +  pseudo + "</strong> : " + message + "</p>"); // on ajoute le message dans la zone prévue

      $('#messages').append("<span style='font-size: 0.8em;' class='label label-default'>" + date +"</span>  <span style='padding-left:10px;'><strong class='label label-success'>" +  pseudo + "</strong></span> " + message + "</p>");  

        

                           /* echo "<p id=\"" . $donnees['id'] . "\"><span style='font-size: 0.8em;'> ".$date->format('d/m H\hi')."</span>  <span  style='padding-left:10px;'><strong class='label label-primary'>" . $donnees['pseudo'] . "</strong></span> " . $donnees['message'] . "</p>";*/

       

        

        document.getElementById("message").value = ""; // supprime le message

        

        element = document.getElementById('table_chat'); // mettre le scroll en bas

        element.scrollTop = element.scrollHeight;

    }

});



function charger(){



    setTimeout( function(){



        var premierID = $('#messages p:last').attr('id'); // on récupère l'id le plus récent
	
        $.ajax({

            url : "chat/charger.php?id=" + premierID, // on passe l'id le plus récent au fichier de chargement

            type : "GET",

            success : function(html){

                $('#messages').append(html);
            }

        });



        charger();

        element = document.getElementById('table_chat');

        element.scrollTop = element.scrollHeight;

    }, 5000);



}



//charger();



var auto_refresh = setInterval(
  function ()
  {
    $('#messages').load('chat/index.php');
  }, 5000); 
 






