
//--------------------------------------------------
// Fonction qui permet d'instancer une nouvelle conversation
//@elem : permet de garder le contexte de la div
//@ idDudistant : id de la personne avec qui l'utilisateur loggé discute
//@nameContact : permet de garer en m
//-------------------------------------------------
function newConvers(elt,iduDistant,element,nameContact,calcOffset,classWithOffset,data){


  $('.contentConvers').html('');
  $('.formSendMess').remove();
  $('.contentConvers').after('<div class="formSendMess"><input id="'+iduDistant+'" type="text" class="msgbox" name="message"></div>')
  paramsClassMessage = [doctype,iduDistant];
  paramsMclassMessage = [];
  
  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'messagerie',method: 'getMessage', paramsClass: JSON.stringify(paramsClassMessage),params: JSON.stringify(paramsMclassMessage)},
  })
  .done(function(msg) {
    isNewMessages();
    
    for(i=0;i<msg.length;i++){

      if(msg[i].utilisateurs_id_distant ==  iduDistant){



        if($('.contentConvers li:last-child').data('id') == msg[i].utilisateurs_id_current){

          $('.contentConvers').append('<li data-id="'+msg[i].utilisateurs_id_current+'" class="liDis"><p><span ">'+msg[i].contenu+'</span></li>');

        }
        else{

          $('.contentConvers').append('<li class="liDis pseudoChat" >'+msg[i].pseudo+'</li><li data-id="'+msg[i].utilisateurs_id_current+'" class="liDis"><p><span ">'+msg[i].contenu+'</span></li>');


        }

        

      }
      

      else{

        if($('.contentConvers li:last-child').data('id') == msg[i].utilisateurs_id_current){

          $('.contentConvers').append('<li data-id="'+msg[i].utilisateurs_id_current+'" class="liCurr"><p><span ">'+msg[i].contenu+'</span></p></li>');

        }
        else{

          $('.contentConvers').append('<li class="liCurr pseudoChat" >'+msg[i].pseudo+'</li><li data-id="'+msg[i].utilisateurs_id_current+'" class="liCurr "><p><span ">'+msg[i].contenu+'</span></li>');


        }

        
      }



    }
    
    var topHeight = $('.contentConvers').prop('scrollHeight');
    $('.contentConvers').animate({scrollTop:topHeight},'100');  



     //-----------------------------------------
     //Je lance l'intervalle pour pinger toute les secondes afin de recuperer les derniers messages
     //------------------------------------------
     setInterval(function(){
      params = [""];
      $.ajax({
        url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
        type: 'POST',
        dataType: 'json',
        data: {clas: 'messagerie',method: 'getLastMessage', paramsClass: JSON.stringify(paramsClassMessage)},

      })
      .done(function(pingback) {


        for(i=0;i<pingback.length;i++){

         if(pingback[i].utilisateurs_id_distant ==  iduDistant){



          if($('.contentConvers li:last-child').data('id') == pingback[i].utilisateurs_id_current){

            $('.contentConvers').append('<li data-id="'+pingback[i].utilisateurs_id_current+'" class="liDis"><p><span ">'+pingback[i].contenu+'</span></li>');

          }
          else{

            $('.contentConvers').append('<li class="liDis pseudoChat" >'+pingback[i].pseudo+'</li><li data-id="'+pingback[i].utilisateurs_id_current+'" class="liDis"><p><span ">'+pingback[i].contenu+'</span></li>');


          }



        }


        else{

          if($('.contentConvers li:last-child').data('id') == pingback[i].utilisateurs_id_current){

            $('.contentConvers').append('<li data-id="'+pingback[i].utilisateurs_id_current+'" class="liCurr"><p><span ">'+pingback[i].contenu+'</span></p></li>');

          }

          else{

            $('.contentConvers').append('<li class="liCurr pseudoChat" >'+pingback[i].pseudo+'</li><li data-id="'+pingback[i].utilisateurs_id_current+'" class="liCurr "><p><span ">'+pingback[i].contenu+'</span></li>');


          }


        }





      }

      if(pingback.length != 0){
        topHeight = $('.contentConvers').prop('scrollHeight');
        $('.contentConvers').animate({scrollTop:topHeight},'slow'); 


      }

    }).fail(function(fail){

      console.log(fail);
    })
  },1000)





});

//-------------------------------------------
// Envoi du message lorsque l'utilisateur appuie sur ENTER 
//-------------------------------------------

$('.msgbox').keypress(function(event){

 if (event.keyCode == 13)  {

  event.preventDefault();
  var idAenvoyer = $(this).attr("id");
  var contenuTxt = $(this).val();

  paramsClass = [doctype,idAenvoyer];
  params = [contenuTxt];

  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'messagerie',method: 'addMessage', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {

    $('.msgbox').val("");
    $('.msgbox').blur();

  })
}
});

}

//------------------------------------
//Tentative de création d'un feed d'actualité, pas d'implantation
//------------------------------------
function loadNewsfeed(){


  $('.posts').html("");  
  $('.detailsProfil').remove();
  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/newsfeedParser.php',
    type: 'POST',
    dataType: 'json',
    

    
  })
  .done(function(data) {



    for(i=0;i<data.length;i++){
     $('.posts').css({'margin':'0 0 0 0'}).append('<li  class="imgPost" style="background: url('+data[i].image+')center center;"><div class="postContainer unchecked"><div class="frontContentNews"><div class="spacerPost"></div><div class="contentPost"><p><a href="'+data[i].url+'" target="blank">'+data[i].title+'</a></p></div></div></div></div></div></li>');
   }
   







 });


}
//-----------------------------------------
//@type : catégorie du mur à charger
//@uid : id de l'utilisateur à envoyer si besoin, pour charger des posts spécifiques
//@pid : id d'un post, pour "charger à partir de", par exemple.
//@clear : nettoyer le mur courant ou non pour charger une nouvelle timeline
//@ptype : gestion d'une particularité pour la gestion du mur de photo de profil
//-----------------------------------------
function loadWall(type,uid,pid,clear,ptype){


  params = [doctype,type,pid,uid];

  if(clear == '1'){
    $('.posts').html("");  
    $('.detailsProfil').remove();
    $('.borderRed').remove();
    vc = 5;
  }


  var classPost = 1;
  var incrModPost = 0;
  
  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data:{clas: 'post',method: 'getPost', paramsClass: '',params: JSON.stringify(params)},

    
  })
  .done(function(data) {


    for(i=0;i<data.length;i++){


      classPost++;
      incrModPost++;

      var idP = data[i].id;
      var idPoste = data[i].img_id;
      
      if(data[i].id == doctype){

        var delVar = '<div data-gvar="'+data[i].id+'" data-id="'+data[i].img_id+'" class="delete deletePicto"></div>';
      }

      else{

        delVar = "";
      }

    //-------------------------------------
    // Je crée un switch qui permet de créer les murs selon ce que l'utilisateur choisi 
    //-------------------------------------

    switch(type){



      case "profile":  


      console.log(ptype);

      if(i == 0){
        check = 0;

        $.ajax({
          url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
          type: 'POST',
          dataType: 'json',
          async: false,
          data: {clas: 'amis',method: 'checkamitie',params: JSON.stringify([data[i].id])},
        })
        .done(function(data) {

          if(data == 1){
            check = 1;
          }

        });

        //------------------------------------
        //Si l'utilisateur choisi de voir son propre mur de photo, avec ce qu'il a posté
        //------------------------------------
        if(ptype == "pperso"){

          $('.posts').append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url('+data[i].root+')center center;"><div class="postContainer unchecked"><div class="commentsPlace"><h1>Commentaires</h1><div class="closeCross"></div><div class="listComments"><ul></ul></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="infoPost"><div class="infoPostContainer"><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');

          break;

        }

        //------------------------------------
        //Si l'utilisateur loggé est déja amis avec le profil qu'il consulte
        //------------------------------------
        if(check == "1"){

         $('.front_scroller').append('<div class="detailsProfil">'+data[i].prenom+' '+data[i].nom+'<button data-id="'+data[i].id+'" class="suppramis">Supprimer en amis</button></div>');

       }

      // Si non

      else{

        $('.front_scroller').append('<div class="detailsProfil">'+data[i].prenom+' '+data[i].nom+'<button data-id="'+data[i].id+'" class="addamis">Ajouter en amis</button></div>');

      }

    }
    

    $('.posts').css({'margin':'0 0 0 35%'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(http://37.187.20.195/~streetzer'+data[i].root+')center center;"><div class="postContainer unchecked"><div class="commentsPlace"><h1>Commentaires</h1><div class="closeCross"></div><div class="listComments"><ul></ul></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="infoPost"><div class="infoPostContainer"><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
    break;


    default:
    $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(http://37.187.20.195/~streetzer'+data[i].root+')center center;"><div class="postContainer unchecked"><div class="commentsPlace"><h1>Commentaires</h1><div class="closeCross"></div><div class="listComments"><ul></ul></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+idP+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
    break;

    case 'wall':
    $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].idpinned_photos+'" class="gen'+classPost+' imgPost" style="background: url(http://37.187.20.195/~streetzer'+data[i].root+')center center;"><div class="postContainer unchecked"><div class="commentsPlace"><h1>Commentaires</h1><div class="closeCross"></div><div class="listComments"><ul></ul></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+idP+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
    break;

    case 'friends':
    $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(http://37.187.20.195/~streetzer'+data[i].root+')center center;"><div class="postContainer unchecked"><div class="commentsPlace"><h1>Commentaires</h1><div class="closeCross"></div><div class="listComments"><ul></ul></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+data[i].utilisateurs_id+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
    break;

  }





 //----------------------------------------------------------------- 
//Tout les 5 posts, je fais un post de la height de 2 posts normaux
//-----------------------------------------------------------------

if(incrModPost == 5){

  $('.gen'+classPost).addClass('bigOne');
  incrModPost = 0;
  
}




    }


  })


}

//------------------------------------------------------------------------
//Ici je crée mon interface de messagerie
//------------------------------------------------------------------------
function Messagerie(){

$('.posts').html("");  

$('body').css('overflow','hidden');

$('.front_scroller ul').prepend('<div class="containerMessages"><div class="listeInsideOnline"><div class="headerMess">MESSAGES</div><div class="containInputSearch"><span class="writenew">WRITE NEW</span><div class="ui-widget"><input placeholder="Chercher un ami ..." class="friendlist"></div></div></div><div class="contentConvers"></div></div>');

$.ajax({
  url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
  type: 'POST',
  dataType: 'json',
  data: {clas: 'messagerie',method: 'getConvers', paramsClass: JSON.stringify([doctype,""]),params: JSON.stringify([])},
})
.done(function(data) {

  for(i=0;i<data.length;i++){

    $('.listeInsideOnline').append("<li><a class='usertag' href='#'><span id='"+data[i].id+"' >"+data[i].nom+"</span></a><div data-id='"+data[i].id+"' class='predeleteConvers deletePicto deleteConvers'></div></li>");


    }
  })
}




//------------------------------------------------------------------------
//Ici je récupere la liste des amis de l'utilisateur
//------------------------------------------------------------------------
function friendsList(){



paramsClass = [doctype];
$('.posts').html("");    
$('.detailsProfil').remove();
$('.front_scroller ul').prepend('<div class="containerMessages"><div class="listeInsideOnline"></div><div class="contentConvers"></div></div>');

$.ajax({

  url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php', 
  type: "POST",
  data:{clas: 'amis',method: 'listAmis', paramsClass: JSON.stringify(paramsClass)},
  dataType: 'json',

})
.done(function(data) {

 for(i=0;i<data.length;i++){
   $('.posts').css({'margin':'0 0 0 0'}).append('<li  class="imgPost" style="background: url('+data[i].pp+'/profil/pp.jpg)center center;"><div class="postContainer unchecked"><div class="frontContentNews"><div class="spacerPost"></div><div class="contentPost"><p data-idU='+data[i].id+' class="showProfile" ">'+data[i].prenom+' '+data[i].nom+'</p></div></div></div></div></div></li>');
 }

}).fail(function(error){

  console.log(error);
})
}





function loadMessages(){
  $('.posts').html("");  

  $('.front_scroller ul').prepend('<div class="containerMessages"><div class="listeInsideOnline"></div><div class="contentConvers"></div></div>');

}

function counter(divCible,cible,postId){


  params = ["","counter",postId,"",divCible];
  $ .ajax({
   url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
   type: 'POST',
               //dataType: 'json',
               
               data: {clas: 'post',method: 'getPost', paramsClass: '',params: JSON.stringify(params)},
             })
  .done(function(data) {

   /* $(cible).find('.showPostComment').html('<span class="counter">'+data+'</span>'); */
   $(cible).find('#'+divCible).html('<span class="counter">'+data+'</span>'); 

        //J'enleve la classe pour que la requete ne s'effectue qu'une fois
       // $(cible).removeClass('unchecked');

     }); 
}

//----------------------------------
// Gestion des notifications pour les nouveau messages
//----------------------------------
function isNewMessages(doctype){

  paramsClass = [doctype];
  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'messagerie',method: 'getNotif', paramsClass: JSON.stringify(paramsClass)},
  })
  .done(function(data) {


    if(data != 0){

      $('.notifsMessages').css({'background':'url(http://37.187.20.195/~streetzer/layout/img/notifMessageOff.png)no-repeat'});
    }
    else{
      $('.notifsMessages').css({'background':'url(http://37.187.20.195/~streetzer/layout/img/notifMessageOn.png)no-repeat'});
    }
  })

  


}
//---------------------------------------------
// Recuperation des commentaires à l'action de l'utilisateur
// @postID : identifie le post pour permettre la requète en base de donnée
// @postClass : identifie le post pour cibler le bon élèment dans le DOM
//---------------------------------------------
function getComments(postID, postClass){


  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/gestion_commentaires/get_comments.php',
    type: 'POST',
    dataType: 'json',
    data: {postId: postID},
  })
  .done(function(data) {
    console.log(data);
    for(i=0;i<data.length;i++){

     $('.gen'+postClass).find('.listComments ul').append('<li>'+data[i].pseudo+'<br><br>'+data[i].contenu+'</li>');




   }
 })


}

$(window).load(function(){

 //s $('.loader').fadeOut();
  loadWall('world','',0,1);

})


$(document).ready(function(){
  
  onTimeline = false;

  $('body').on('click','.front_scroller',function(){

    if(onTimeline == false){


      onTimeline = true;

    $('.sidebar, .logOut').animate({left: '-=100%'},500);
    $('.front_scroller').append('<div class="container_interact_streetlane"><div class="add_mobile_photo"></div><div class="show_mobile_main"><output style="position: absolute; margin-left: 10px;""id="filesInfo"></output><div id="jti"  style="width:157px; height:210px;margin: 0 0 0 9px; overflow:hidden; background-repeat: no-repeat;""><div class="containSpinner"><div class="spinner"></div></div><input type="file" id="imgInp" accept="image/*"" name="postImg" style="font-size:600px; opacity:0; -moz-opacity:0; filter:alpha(opacity=0); float:right; cursor:pointer" /><output id="filesInfo"></output><iframe id="ifr1" name="send" style="width:1px; height:1px;""></iframe></div><div class="clearfix"></div></div><div class="add_mobile_map"></div></div><div class="clearfix"></div>')
    
    }
    


  })
    $('body').on('click','.add_mobile_photo',function(){


   $('.sidebar, .logOut').animate({left: '0'},500,function(){


      onTimeline = false;

   });     

    })
  //Plugin de compatibilité pour le placeholder HTML5
  $('input, textarea').placeholder();


  //-------------------------------
  // Gestion de la liste d'amis pour la messagerie avec autocomplete sur le champ
  //rechercher
  //------------------------------
  $('body').on('keyup','.friendlist',function(){
    var ctnvar = $(this).val();
    


    if($('.friendlist').length < 3){

      $(".friendlist").autocomplete({

        minLength: 2,
        scrollHeight: 220, 

        source: function(req, add){

          $.ajax({
            url:'http://37.187.20.195/~streetzer/resources/dispatcher.php',
            type:"post",
            dataType: 'json',
            data: {clas: 'amis',method: 'listAmisSuggest', paramsClass: JSON.stringify([doctype]),params: JSON.stringify([ctnvar])},
            async: true,
            cache: true,
            success: function(data){

              add($.map(data, function(i){  

                cprenom = i.prenom;
                cnom = i.nom;
                return i.prenom+' '+i.nom;




              }));  



            },

          });
        },
        select: function (event, ui){
         
          $.ajax({
            url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
            type: 'POST',
            dataType: 'json',
            data: {clas: 'messagerie',method: 'getid',params: JSON.stringify([ui.item.value])},
          })
          .done(function(data) {

           

            $('.containInputSearch').append('<div class="newMessagefromSuggest"><textarea class="msgbox" id="contentNewMess" placeholder="Ecrivez votre message ici et appuyez sur ENTER" cols="46" rows="10"></textarea><button id="'+data.id+'" class="submitnewmessage">Envoyer</button></div>')
          })


        }

      });

}

})

$('body').on('click','.submitnewmessage',function(){

  var idAenvoyer = $(this).attr("id");
  var contenuTxt = $('#contentNewMess').val();

  paramsClass = [doctype,idAenvoyer];
  params = [contenuTxt];

  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'messagerie',method: 'addMessage', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {

    Messagerie();
    $('#contentNewMess').remove();
    $('submitnewmessage').remove();
    $('.friendlist').val("");
    $('.msgbox').val("");
    $('.msgbox').blur();
  })


})







doctype = sessionStorage.getItem("globalArrayValue");
isNewMessages(doctype);

//----------------------------
// Je déclence le ping pour les nouvelles notifications
//----------------------------
setInterval(function(){

  isNewMessages(doctype);

},10000)


//-----------------------------------------------
// Permet l'affichage de l'image dans une div quand l'utilisateur la choisi sur sa machine
//-----------------------------------------------
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
            //$('#render').attr('src', e.target.result);
            $('#render').css({'background':'url('+e.target.result+')center center'});
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $("body").on('change','#imgInp',function(){
        $('.front_scroller ul li').remove();
        $('#render').fadeIn();
        $('.blocMessage input[type="text"], .blocMessage button[type="submit"]').fadeIn();
        readURL(this);
      
      });


//-----------------------------------------------
// Action lorsque l'utilisateur clique sur l'icone pour voir sa messagerie
//-----------------------------------------------
      
      $('body').on('click','.showMessages, .notifsMessages', function(){

        $('.posts').html("");  
        $('.detailsProfil').remove();
        $('.borderRed').remove();
        $('.posts').css({'margin':'0'});
        $('.front_scroller').css('width','50%').append('<div class="borderRed"></div>');
        
        Messagerie();

      })
      


      $('body').on('click','.pictoComm', function(){
       c = $(this).parent().data('id');
       console.log(c);


       $('.toggleInput'+c+'').animate({'right':'400px'}, 1000);

     })
      
      
      $('body').on('click', '.laneFriends', function (e){
      
       loadWall('friends','','0',1);

     })

      $('body').on('click', '.listFriends', function (e){

       friendsList();

     })

      $('body').on('click', '#comments', function (e){

        var idPost = $(this).data('genc');
        var idClass = $(this).data('id');
        console.log(idClass);   
        console.log(idPost);   
        $('.gen'+idClass+' .commentsPlace').fadeIn(500);
        $('.gen'+idClass+' .commentsPlace').animate({'top':'0'},500);
        $('.listComments ul').html("");
        getComments(idPost, idClass);
        



      })


      $('body').on('click', '.showProfile', function (e){


        var idUser = $(this).data('idu');
        $('.listComments ul').html("");
        loadWall('profile',idUser,'0',1,"");



      })

//---------------------------------------------
// Gestion d'ajout et de supression d'un amis
//---------------------------------------------
          $('body').on('click', '.addamis', function (e){


            var idUser = $(this).data('id');

            console.log(idUser);
            $('.listComments ul').html("");
            paramsClass = [doctype];
            params = [idUser];
            $.ajax({
              url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
              type: 'POST',
              data: {clas: 'amis',method: 'ajoutAmis', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
            })        
          })

          $('body').on('click', '.suppramis', function (e){


            var idUser = $(this).data('id');

            console.log(idUser);
            $('.listComments ul').html("");

            paramsClass = [doctype];
            params = [idUser];
            $.ajax({
              url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
              type: 'POST',
              data: {clas: 'amis',method: 'suppramis', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
            })

          })

$('body').on('keypress', '.contentCommTs', function (e){

  if (event.keyCode == 13)  {
    var pclass = $(this).data('genc');
    var idPost = $(this).data('id');
    idClass = $(this).data('genc');
    var contenuComm = $(this).val();
    $(this).val(" ");

   
    $('.listComments ul').html("");

    $.ajax({
      url: 'http://37.187.20.195/~streetzer/resources/gestion_commentaires/add_entry.php',
      type: 'POST',
      data: {contenu: contenuComm, postId: idPost},
    })
    .done(function(data) {


      $('.toggleInput'+c+'').animate({'right':'0'}, 1000);
      $('.gen'+pclass+' .commentsPlace').fadeIn(500);
      $('.gen'+pclass+' .commentsPlace').animate({'top':'0'},500);
    
      getComments(idPost, idClass);
    })
  }
})



//--------------------------------------------
//Je fais apparaitre l'overlay qui presente le coeur et le bouton commentaire
//--------------------------------------------
$('body').on({
  mouseenter: function () {
   $(this).find('.frontContent').hide();
   $(this).find('.hov').show();
   
   cible = $(this);
   var postId = $(this).find('.contentCommTs').data('id');
   var uid = $(this).find('.showProfile').data('idu');



   paramsClass = [postId,doctype];

       //--------------------------------------------
       //Je recupere le nombre de commentaire pour l'afficher dans la div commentaires
       //--------------------------------------------
       $.ajax({
         url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
         type: 'POST',

         data: {clas: 'wall',method: 'filterLike', paramsClass: JSON.stringify(paramsClass),params: ""},
       })
       .done(function(data) {

        if($.trim(data) == "pinned"){

          $(cible).find('.stick').removeClass('a').addClass('b').addClass('pinned');
          
        }

      })

       if($(this).hasClass('unchecked')){

         counter('comments',$(this),postId);
         counter('coeur',$(this),postId);

       }

     },
     mouseleave: function () {
       $(this).find('.frontContent').show();
       $(this).find('.hov').hide();
       $('.commentsPlace').animate({'top':'100%'}, 500);
       //$('.containerPicto').animate({'right':'-45px'},10);
       //$('.infoPost').animate({'bottom':'-35px'},10);

     }
   }, ".postContainer");


//---------------------------------------------
//Au clic, le post est ajouté sur le streetwall
//--------------------------------------------
$('body').on('click', '.stick', function (e){

  var id = $(this).data('id');  
  ctn = $(this);
  var type="";
  e.preventDefault();
  
  if($(this).hasClass('unpinned')){
    type = "add";
  }
  if($(this).hasClass('pinned')){
    type = "delete";
  }
  paramsClass = [id,doctype];
  params = [type];

  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',

    data: {clas: 'wall',method: 'filterLike', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {
   

    if($.trim(data) == "pinnedok"){
      $(ctn).removeClass('b').addClass('a').removeClass('pinned').addClass('unpinned');
      if($(cible).parent().data('type') == "wall"){

        $(cible).parent().fadeOut(function(){

          $(this).remove();

        })
      }
    }
    if($.trim(data) == "unpinned"){
      $(ctn).removeClass('a').addClass('b').removeClass('unpinned').addClass('pinned');
    }
    counter('coeur',cible,id);
    return;


  });

  


})

//--------------------------------------------
//Au clic, le flux de tout les utilisateurs est affiché
//--------------------------------------------
$('.laneWorld').click(function(e) {
  e.preventDefault();
  loadWall('world','',"0",1);
});

//--------------------------------------------
//Au clic, le flux du streetwall de la personne est affiché
//--------------------------------------------
$('.photoWall').click(function(e) {
  e.preventDefault();
  loadWall('wall','',"0",1);
});

$('.newsLane').click(function(e) {
  e.preventDefault();
  //loadNewsfeed();  
});



var offsetInc = 0;



//------------------------------------------------------------------------
// Au clic, je charge la conversation
//------------------------------------------------------------------------

$('body').on('click', '.usertag', function (e){



  e.preventDefault(e)

  var elt = $(this);
  var iduDistant = $(this).find('span').attr('id');
  var element = $('.stackChat');

  
  var nameContact = $(this).text();
  var calcOffset = (205 * offsetInc);

  var classWithOffset = "fenetrechat"+offsetInc;
  var data = $(this).html();

  newConvers(elt,iduDistant,element,nameContact,calcOffset,classWithOffset,data);


});









//------------------------------------------------------------------------
// J'affiche l'image choisi par l'utilisateur dans la div aprés qu'il ai choisi ce qu'il veux envoyer , et filtre sa taille
//------------------------------------------------------------------------

$("body").on("change","#imgInp", function() {


 file = this.files[0];

 size = this.files[0].size;
 if(size > 2000000){

  alert('Votre image ne doit pas faire plus de 2MO');


  $('.blocMessage form').remove();


  $('.blocMessage').append("<form><output style='position: absolute; margin-left: 10px;' id='filesInfo'></output><div id='jti'  style='width:157px; height:210px;margin: 0 0 0 9px; overflow:hidden; background-repeat: no-repeat;'><input type='file' id='imgInp' accept='image/*' name='postImg' style='font-size:600px; opacity:0; -moz-opacity:0; filter:alpha(opacity=0); float:right; cursor:pointer' /><output id='filesInfo'></output><iframe id='ifr1' name='send' style='width:1px; height:1px;'></iframe></div><input class='ctnForm' type='text' placeholder='Entrez votre message...'' name='contenu'></input><img id='render' src='http://37.187.20.195/~streetzer/layout/img/pisdead.png' alt='your image' /><div class='clearfix'></div><button style='position: relative;top:-200px' class='postSend' style='float: left;'>Envoyer</button</form>");

}





})  

//------------------------------------------------------------------------
// J'envoi le formulaire pour créer un nouveau post avec une nouvelle photo
//------------------------------------------------------------------------

$('body').on('keypress','.ctnForm',function(e){

  if (event.keyCode == 13)  {

    var formdata = new FormData();    
    var ctn = $(this).val();
    paramsClass = [doctype,ctn];

    if (formdata) {
      formdata.append("postImg", file);
      formdata.append('clas', 'post');
      formdata.append('method', 'newPost');
      formdata.append('paramsClass', JSON.stringify(paramsClass));
            //params = [formdata];
            jQuery.ajax({
              url: "http://37.187.20.195/~streetzer/resources/dispatcher.php",
              type: "POST",
              data: formdata,
              processData: false,
              contentType: false,
              beforeSend:function(){

               $('#render').fadeOut();
               $('.ctnForm').val('');

               $('.containSpinner').fadeIn();

             },
             success:function(){

              $('.containSpinner').fadeOut();
              $('.blocMessage input[type="text"], .blocMessage button[type="submit"]').fadeOut();

              loadWall("world",'',"0",1);

              formdata = null;

              file = null;

            }

          });
          } 
        }
      })

$('body').on('click','.delete',function(){

  var pid = $(this).data('id');
  var uid = $(this).data('gvar');

  paramsClass = [doctype];
  params = [pid,uid];

  var elem = $(this).parent().parent().parent().parent();
  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    async: false,
    data: {clas: 'post',method: 'deletePost', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {
    console.log(data);
    elem.fadeOut();
  })

  

})

//------------------------------------------------------------------------
// Deconnexion
//------------------------------------------------------------------------
$('.logOut a').click(function(e){

  e.preventDefault();

  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/logout.php',

  })
  .done(function() {
    window.location = "../";
    sessionStorage.clear();
  })
  


})


//----------------------------------------
// Affichage des posts de l'utilisateur
//----------------------------------------
$('body').on('click','.nameSurname', function(){

  uid = $(this).data('id');
  loadWall('profile',uid,'',1,"pperso");


})

$('body').on('click','.deleteConvers', function(){

  var conf = confirm("Etes vous sur de vouloir effacer cette conversation ? L'action est irrémédiable. ");
  if(conf == false){

    return;
  }
  var uid = $(this).data('id');
  var params = [uid];

  $.ajax({
    url: 'http://37.187.20.195/~streetzer/resources/dispatcher.php',
    type: 'POST',
    datatype: 'json',
    data: {clas: 'messagerie',method: 'supprConvers',params: JSON.stringify(params)},
  })
  .done(function() {
   Messagerie();
 })

  
  

})

});




