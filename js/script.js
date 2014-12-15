init = 0;
soundnotif = 0;
checkmess = null;
isset = 0;
boxsize = "";

function layout(size){

  switch (size){

    case "petit":
    $('.posts .imgPost').css({'width':'400px!important','height':'32.7%'});
    $('.posts').css({'width':'400px'});
    $('.bigOne').css({'height':'32.7%'});
    $('.contentPost p').css({'font-size':'0.8em'});
    break;
    case "moyen":
    $('.posts .imgPost').css({'width':'478px!important','height':'49.5%'});
    $('.posts').css({'width':'478px'});
    $('.bigOne').css({'height':'99.8%'});
    $('.contentPost p').css({'font-size':'1em'});
    break;
    case "grand":
    $('.posts .imgPost').css({'width':'650px!important','height':'99%'});
    $('.posts').css({'width':'650px!important'});
    $('.bigOne').css({'height':'99.8%'});
    $('.contentPost p').css({'font-size':'1em'});
    break;
    default:
    $('.posts .imgPost').css({'width':'400px!important','height':'32.7%'});
    $('.posts').css({'width':'400px'});
    $('.bigOne').css({'height':'32.7%'});
    $('.contentPost p').css({'font-size':'1em'});
    break;


  }

}


function horizontalScroll(){

  if( init ==1){
    return;
  }

  $('html, body,document,window, *').mousewheel(function(e, delta) {

    e.preventDefault();

    this.scrollLeft -= (delta *30);

    var tailleB = $(document).width();
    type = $('.posts li').data('type');
    userid = $('.profilNP').data('id');

  //Je récupere l'id du dernier post de ma liste
  var i = $('.posts > li:last-child').data('id');

  var v = 0;

  //--------------------------------------------------
  //Je scan le DOM et à chaque "bigOne"( grosse div ), j'incrémente v et j'ajoute la classe
  //--------------------------------------------------
  $('.bigOne').each(function(){
    v = v + 5;
    $(this).addClass('t'+v);

  })
  
  var offset = $('.t'+vc).offset();
  var w = $(window);
  var o = offset.left-w.scrollLeft();


  //----------------------------------------------
  //@o : correspond à la position x y de la div bigOne, si il descend en dessous de 500, je charge de nouveaux posts
  //----------------------------------------------
  if(o < 500){

    switch(type){

      default:
      loadWall(type,'',i,0);
      break;
      case "profile":
      loadWall(type,userid,i,0);
      break;
    }
    
    vc = vc+5; 

  }




});

}
function loadCommentsOnSolo(postId){

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'commentaires',method: 'getComments', params: JSON.stringify([postId])},
  })
  .done(function(datacomments) {
    $('.soloComments').html("");
    for(i=0;i<datacomments.length;i++){
     $('.soloComments').append('<li><span style="font-weight:800;">'+datacomments[i].pseudo+'</span><br><span style="font-weight:100;">'+datacomments[i].contenu+'</span></li>')
   }

 }).fail(function(lol){

  console.log(lol);
})
}


function displayPic(postId){

  $('body,html,*').animate({scrollLeft:'0'},200);
  $('.ppic,.description').remove();
  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'post',method: 'displayPost',params: JSON.stringify([postId])},
  })
  .done(function(data) {
    console.log(data);
    $('.posts li').fadeOut(200).remove();
    for(i=0;i<data.length;i++){

      if(data[i].type == "youtube"){

        $('.front_scroller').css({'width':'auto'}).append('<div class="deleteSolo"><div class="containerInputComm"><input style="position: absolute;z-index:9999;display:block;color: white;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-id="'+data[i].img_id+'" type="text"/></div><div class="soloComments"></div><div style="background:url(../images/'+data[i].rootpic+'/post_pic/'+data[i].img_id+'.jpg)no-repeat center center " class="soloDisplay"><iframe style="width:100%;height:100%;" width="560" height="315" src="'+data[i].embed+'" frameborder="0" allowfullscreen></iframe></div><div class="soloProfil"><div data-id="'+data[i].id+'" style="background:url(../images/'+data[i].rootpic+'/profil_pic/pp.jpg)no-repeat center center; "class="soloPhotoProfil"></div><div class="soloProfilDetails"><h4>'+data[i].pseudo+'</h4><br/><h6> le '+data[i].datepost+'</h6></div></div></div>');

      }
      else{

        $('.front_scroller').css({'width':'auto'}).append('<div class="deleteSolo"><div class="containerInputComm"><input style="position: absolute;z-index:9999;display:block;color: white;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-id="'+data[i].img_id+'" type="text"/></div><div class="soloComments"></div><div style="background:url(../images/'+data[i].rootpic+'/post_pic/'+data[i].img_id+'.jpg)no-repeat center center " class="soloDisplay"></div><div class="soloProfil"><div style="background:url(../images/'+data[i].rootpic+'/profil_pic/pp.jpg)no-repeat center center; "class="soloPhotoProfil"></div><div class="soloProfilDetails"><h4>'+data[i].pseudo+'</h4><br/><h6> le '+data[i].datepost+'</h6></div></div></div>');
      }

    }
    
    loadCommentsOnSolo(postId);

    

  })



}
//--------------------------------------------------
// Fonction qui permet d'instancer une nouvelle conversation
//@elem : permet de garder le contexte de la div
//@ idDudistant : id de la personne avec qui l'utilisateur loggé discute
//@nameContact : permet de garer en m
//-------------------------------------------------
function newConvers(elt,iduDistant,element,nameContact,calcOffset,classWithOffset,data){

  checkBoucle = 0;
  $('.contentConvers').html('');
  $('.formSendMess').remove();
  $('.contentConvers').after('<div class="formSendMess"><input id="'+iduDistant+'" type="text" class="msgbox" name="message"></div>')
  paramsClassMessage = [doctype,iduDistant];
  paramsMclassMessage = [];

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    async:false,
    data: {clas: 'messagerie',method: 'getMessage', paramsClass: JSON.stringify(paramsClassMessage),params: JSON.stringify(paramsMclassMessage)},
  })
  .done(function(msg) {

    console.log(msg);
    var listid_init = [];
    isNewMessages();
    
    $('.contentConvers li').each(function(){

      listid_init.push($(this).data('mid'));
      
    })
    
    for(i=0;i<msg.length;i++){

      checkBoucle++;

      
      if($.inArray(msg[i].idchat_messages,listid_init) != -1){


      }
      else{

        if(msg[i].utilisateurs_id_distant ==  iduDistant){

          if($('.contentConvers li:last-child').data('id') == msg[i].utilisateurs_id_current){

            $('.contentConvers').append('<li data-id="'+msg[i].utilisateurs_id_current+'" data-convid="'+msg[i].idconvers+'" data-mid="'+msg[i].idchat_messages+'" class="liDis"><p><span ">'+msg[i].contenu+'</span></li>');

          }
          else{

            $('.contentConvers').append('<li class="liDis pseudoChat" >'+msg[i].pseudo+'<span style="font-size:0.7em;float:right;">'+msg[i].datepost+'</span></li><li data-id="'+msg[i].utilisateurs_id_current+'" data-convid="'+msg[i].idconvers+'" data-mid="'+msg[i].idchat_messages+'"class="liDis"><p><span ">'+msg[i].contenu+'</span></li>');


          }



        }


        else{

          if($('.contentConvers li:last-child').data('id') == msg[i].utilisateurs_id_current){

            $('.contentConvers').append('<li data-id="'+msg[i].utilisateurs_id_current+'" data-convid="'+msg[i].idconvers+'" data-mid="'+msg[i].idchat_messages+'" class="liCurr"><p><span ">'+msg[i].contenu+'</span></p></li>');

          }
          else{

            $('.contentConvers').append('<li class="liCurr pseudoChat" >'+msg[i].pseudo+'<span style="font-size:0.7em;float:right;">'+msg[i].datepost+'</span></li><li data-id="'+msg[i].utilisateurs_id_current+'" data-convid="'+msg[i].idconvers+'" data-mid="'+msg[i].idchat_messages+'" class="liCurr "><p><span ">'+msg[i].contenu+'</span></li>');


          }

          
        }

      }

    }
    
    var topHeight = $('.contentConvers').prop('scrollHeight');
    $('.contentConvers').animate({scrollTop:topHeight},'100');  



     //-----------------------------------------
     //Je lance l'intervalle pour pinger toute les secondes afin de recuperer les derniers messages


     //------------------------------------------
     
     if(checkBoucle < msg.length - 1 ){
      console.log(checkBoucle);
      console.log(msg.length);
      console.log("estoy aqui");
    }
    else{

     checkmess =  setInterval(function(){
      params = [""];


      $.ajax({
        url: '../resources/dispatcher.php',
        type: 'POST',
        dataType: 'json',
        data: {clas: 'messagerie',method: 'getLastMessage', paramsClass: JSON.stringify(paramsClassMessage)},

      })
      .done(function(pingback) {
        var listid = [];
        $('.contentConvers li').each(function(){

          listid.push($(this).data('mid'));
        })

        for(i=0;i<pingback.length;i++){




   //   if( $.inArray(pingback[i].idchat_messages,listid) != -1){


    //  }
      // else{
       if(pingback[i].utilisateurs_id_distant ==  iduDistant){



        if($('.contentConvers li:last-child').data('id') == pingback[i].utilisateurs_id_current){

          $('.contentConvers').append('<li data-id="'+pingback[i].utilisateurs_id_current+'" data-convid="'+pingback[i].idconvers+'" data-mid="'+pingback[i].idchat_messages+'" class="liDis"><p><span ">'+pingback[i].contenu+'</span></li>');

        }
        else{

          $('.contentConvers').append('<li class="liDis pseudoChat" >'+pingback[i].pseudo+'<span style="font-size:0.7em;float:right;">'+pingback  [i].datepost+'</span></li><li data-id="'+pingback[i].utilisateurs_id_current+'" data-convid="'+pingback[i].idconvers+'" data-mid="'+pingback[i].idchat_messages+'" class="liDis"><p><span ">'+pingback[i].contenu+'</span></li>');


        }



      }


      else{

        if($('.contentConvers li:last-child').data('id') == pingback[i].utilisateurs_id_current){

          $('.contentConvers').append('<li data-id="'+pingback[i].utilisateurs_id_current+'" data-convid="'+pingback[i].idconvers+'" data-mid="'+pingback[i].idchat_messages+'" class="liCurr"><p><span ">'+pingback[i].contenu+'</span></p></li>');

        }

        else{

          $('.contentConvers').append('<li class="liCurr pseudoChat" >'+pingback[i].pseudo+'<span style="font-size:0.7em;float:right;">'+pingback[i].datepost+'</span></li><li data-id="'+pingback[i].utilisateurs_id_current+'" data-convid="'+pingback[i].idconvers+'" data-mid="'+pingback[i].idchat_messages+'" class="liCurr "><p><span ">'+pingback[i].contenu+'</span></li>');


        }


      }


        //  }

      }

      if(pingback.length != 0){
        topHeight = $('.contentConvers').prop('scrollHeight');
        $('.contentConvers').animate({scrollTop:topHeight},'slow'); 


      }

    }).fail(function(fail){

      console.log(fail);
    })
  },100)


}






}).fail(function(fail){

  console.log(fail);
});

//-------------------------------------------
// Envoi du message lorsque l'utilisateur appuie sur ENTER 
//-------------------------------------------

$('.msgbox').keypress(function(e){
  e = e || window.event;
  if (e.keyCode == 13)  {

    e.preventDefault();
    var conv = $('.contentConvers li:last-child').data('convid');
    var idAenvoyer = $(this).attr("id");
    var contenuTxt = $(this).val();

    paramsClass = [doctype,idAenvoyer];
    params = [contenuTxt,conv];

    $.ajax({
      url: '../resources/dispatcher.php',
      type: 'POST',
      data: {clas: 'messagerie',method: 'addMessage', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
    })
    .done(function(data) {
      console.log(data);
      if(data == "spam"){

        alert('Veuillez attendre 120 secondes avant de poster un autre message.');
      }
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
    url: '../resources/newsfeedParser.php',
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



  clearInterval(checkmess);
  
  horizontalScroll();
  init = 1;
  params = [doctype,type,pid,uid];

  if(clear == '1'){

    $('.posts').html("");  
    $('.detailsProfil,.borderRed,.ppic,.addamis,.suppramis,.description,.deleteSolo,.containerNotifs').remove();
    $('.tbord_container').remove();
    $('body,html,*').animate({scrollLeft:'0'},200);


    vc = 5;
  }


  var classPost = 1;
  var incrModPost = 0;
  
  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data:{clas: 'post',method: 'getPost', paramsClass: '',params: JSON.stringify(params)},
    beforeSend:function(){$('.loadWall').fadeIn();},

  })
  .done(function(data) {

   $('.loadWall').fadeOut();
   
   if(pid == 0 && data.length == 0){

    $('.posts').html('<h1 class="nopost">Aucun post à afficher !</h1>');
  }

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

      $('.front_scroller').css('width','100%');
      console.log(ptype);

      if(i == 0){
        check = 0;

        $.ajax({
          url: '../resources/dispatcher.php',
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

          $('.posts').css({'margin':'0 0 0 35%'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><ul style="margin: 7px 44px 0 0" class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');

          break;

        }

        //------------------------------------
        //Si l'utilisateur loggé est déja amis avec le profil qu'il consulte
        //------------------------------------
        if(check == "1"){

          $('.front_scroller').append('<div style="background:url(../images/'+data[i].c1+'/profil_pic/pp.jpg)no-repeat;background-size:cover;" class="ppic"><div style="position: absolute;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:2;"></div><div class="suppramis"></div><div class="detailsProfil"><p data-id="'+data[i].id+'" class="profilNP">'+data[i].prenom+' '+data[i].nom+'</p></div></div><div class="description"><h3>A propos de moi</h3><br/><p>Véhicule : '+data[i].vehicule+'<br><br><p>Marque / modèle : '+data[i].marque_vehicule+'</p><br><p>Bio : '+data[i].description+'</p></div>');

        }

      // Si non

      else{

       $('.front_scroller').append('<div style="background:url(../images/'+data[i].c1+'/profil_pic/pp.jpg)no-repeat;background-size:cover;" class="ppic"><div style="position: absolute;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:2;"></div><div class="addamis"></div><div class="detailsProfil"><p data-id="'+data[i].id+'"  class="profilNP">'+data[i].prenom+' '+data[i].nom+'</p></div></div><div class="description"><h3>A propos de moi</h3><br/><p>Véhicule : '+data[i].vehicule+'<br><br><p>Marque / modèle : '+data[i].marque_vehicule+'</p><br><p>Bio : '+data[i].description+'</p></div>');

     }

   }

   if(ptype != "pperso"){
     console.log(data);  
     $('.posts').css({'margin':'0 0 0 35%'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><ul style="margin: 7px 44px 0 0" class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');

     break;

   }




   default:
   $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+idP+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
   break;

   case 'wall':
   $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id  +'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+idP+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
   break;

   case 'friends':

   $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+data[i].utilisateurs_id+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');
   break;

   case 'notifs':

   $('.posts').css({'margin':'0 0 0 0'}).append('<li data-type="'+type+'" data-id="'+data[i].img_id+'" class="gen'+classPost+' imgPost" style="background: url(..'+data[i].root+')center center;"><div class="postContainer dsolo unchecked"><div class="commentsPlace"><div class="closeCross"></div><div class="listComments"><ul></ul><input style="position: absolute;bottom: 0;" placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/></div></div><div class="frontContent"><div class="spacerPost"></div><div class="contentPost"><p>'+data[i].contenu+'</p></div></div><div class="hov"><div class="containerPicto"><div data-id="'+data[i].img_id+'" class="stick a unpinned"></div>'+delVar+'<div data-id="'+classPost+'" class="showPostInput toggleInput'+classPost+'"><div class="pictoComm"></div><input placeholder="Entrez votre commentaire ici ..." class="contentCommTs" data-genc="'+classPost+'" data-id="'+data[i].img_id+'" type="text"/><!--<input data-genc="'+classPost+'" data-id="'+data[i].img_id+'" class="commSubmit" type="submit"/>--></div></div><div class="showMeTheDetails"></div><div class="infoPost"><div class="infoPostContainer"><p class="showProfile" data-idU="'+data[i].utilisateurs_id+'">'+data[i].pseudo+'</p><ul class="hcContainer"><li id="coeur"></li><li data-id="'+classPost+'" data-genc="'+data[i].img_id+'" id="comments"></li></ul></div></div></div></div></li>');



   break;

 }





 //----------------------------------------------------------------- 
//Tout les 5 posts, je fais un post de la height de 2 posts normaux
//-----------------------------------------------------------------

if(incrModPost == 5){

  $('.gen'+classPost).addClass('bigOne');
  incrModPost = 0;
  
}


if(isset != 1){

  layout('moyen');
}
else{
  layout(boxsize);
}
$('.front_scroller').css({'width':'0px'});
var docwidth = $(document).width();
$('.front_scroller').css({'width': docwidth+'px'});

}


})


}

//------------------------------------------------------------------------
//Ici je crée mon interface de messagerie
//------------------------------------------------------------------------
function Messagerie(){

  $('.posts').html("");  
  $('.ppic').remove();
  $('body').css('overflow','hidden');

  $('.front_scroller ul').prepend('<div class="containerMessages"><div class="listeInsideOnline"><div class="headerMess">MESSAGES</div><div class="containInputSearch"><span class="writenew">WRITE NEW</span><div class="ui-widget"><input placeholder="Chercher un ami ..." class="friendlist"></div></div></div><div class="contentConvers"></div></div>');

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'messagerie',method: 'getConvers', paramsClass: JSON.stringify([doctype,""]),params: JSON.stringify([])},
    beforeSend:function(){$('.loadWall').fadeIn();},
  })
  .done(function(data) {
    console.log(data);
    $('.loadWall').fadeOut();
    for(i=0;i<data.length;i++){

      if(data[i].new_message == "1"){

        $('.listeInsideOnline').append("<li><a class='usertag newConv' href='#'><span data-cid='"+data[i].link+"' id='"+data[i].id+"' >"+data[i].nom+"</span></a><div data-id='"+data[i].id+"' class='predeleteConvers deletePicto deleteConvers'></div></li>");
      }
      else{

        $('.listeInsideOnline').append("<li><a class='usertag' href='#'><span data-cid='"+data[i].link+"' id='"+data[i].id+"' >"+data[i].nom+"</span></a><div data-id='"+data[i].id+"' class='predeleteConvers deletePicto deleteConvers'></div></li>");
      }



    }
  })
}




//------------------------------------------------------------------------
//Ici je récupere la liste des amis de l'utilisateur
//------------------------------------------------------------------------
function friendsList(){

  $('body,html,*').animate({scrollLeft:'0'},200);
  clearInterval(checkmess);
  paramsClass = [doctype];
  $('.posts').html("");    
  $('.detailsProfil').remove();
  $('.front_scroller ul').prepend('<div class="containerMessages"><div class="listeInsideOnline"></div><div class="contentConvers"></div></div>');

  $.ajax({

    url: '../resources/dispatcher.php', 
    type: "POST",
    data:{clas: 'amis',method: 'listAmis', paramsClass: JSON.stringify(paramsClass)},
    dataType: 'json',

  })
  .done(function(data) {
    if(data.length == 0){

      $('.posts').html('<h1 class="nofriends">Vous ne suivez encore personne !</h1>');
    }

    for(i=0;i<data.length;i++){
     $('.posts').css({'margin':'0 0 0 0'}).append('<li  class="imgPost" style="background: url(../images/'+data[i].pp+'/profil_pic/pp.jpg)center center;"><div class="postContainer unchecked"><div class="frontContentNews"><div class="spacerPost"></div><div class="contentPost"><p data-idU='+data[i].id+' class="showProfile" ">'+data[i].prenom+' '+data[i].nom+'</p></div></div></div></div></div></li>');
   }
   $('.front_scroller').css({'width':'0px'});
   var docwidth = $(document).width();
   $('.front_scroller').css({'width': docwidth+'px'});

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
   url: '../resources/dispatcher.php',
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
    url: '../resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'messagerie',method: 'getNotif', paramsClass: JSON.stringify(paramsClass)},
  })
  .done(function(data) {


    if(data != 0){

      $('.showMessages').css({'border-left':'2px solid #e7434b'}).html('Messages '+data);
      if(soundnotif == 0 ){

       var audioElement = document.createElement('audio');
       audioElement.setAttribute('src', '../css/notif.ogg');
       audioElement.play();
       document.title = "Nouveau(x) message(s) | Streetzer ";
       soundnotif++;
     }

   }
   else{
    $('.notifsMessages').css({'background':'url(../layout/img/notifMessageOn.png)no-repeat'});
    document.title = " Streetzer | Home ";
    soundnotif = 0;
  }
})

  


}
//----------------------------------
// Gestion des notifications les nouveaux likes et les nouveaux commentaires
//----------------------------------
function isNewStuffOnMyPosts(){


  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'utilisateur',method: 'getPostNotifs',params:JSON.stringify(['check'])},
  })
  .done(function(data) {


    if(data != 0){

      $('.notifs').css({'background':'url(../layout/img/notifOn.svg)no-repeat'});
    }
    else{
      $('.notifs').css({'background':'url(../layout/img/notifOff.svg)no-repeat'});
    }
  })

  


}
//---------------------------------------------
// Recuperation des commentaires à l'action de l'utilisateur
// @postID : identifie le post pour permettre la requète en base de donnée
// @postClass : identifie le post pour cibler le bon élèment dans le DOM
//---------------------------------------------
function getComments(postID, postClass){

  var params = [postID];
  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'commentaires',method: 'getComments', params: JSON.stringify(params)},
  })
  .done(function(data) {
    if(data.length==0){

      $('.gen'+postClass).find('.listComments ul').append('<li><span style="font-weight:800;">Aucun commentaire</span></li>');

    }
    for(i=0;i<data.length;i++){


     $('.gen'+postClass).find('.listComments ul').append('<li><span style="font-weight:800;">'+data[i].pseudo+'</span><br><span style="font-weight:100;">'+data[i].contenu+'</span></li>');




   }
   var topHeight = $('.listComments').prop('scrollHeight');
   $('.listComments').animate({scrollTop:topHeight},'100');  
 })


}

$(window).load(function(){

  $('.loader').fadeOut();
  loadWall('world','',0,1);

})


$(document).ready(function(){


  $('body').on('click','.showMeTheDetails',function(){
    currentScroll =  $('body,html,*').scrollLeft();
    var postid = $(this).parent().parent().parent().data('id');


    displayPic(postid);


  })


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
            url:'../resources/dispatcher.php',
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
        select: function (event,ui){
          console.log(ui);
          $.ajax({
            url: '../resources/dispatcher.php',
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
    url: '../resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'messagerie',method: 'addMessage', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {
    console.log(data);
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
isNewStuffOnMyPosts();
//----------------------------
// Je déclence le ping pour les nouvelles notifications
//----------------------------
setInterval(function(){

  isNewMessages(doctype);
  isNewStuffOnMyPosts();

},100)


//-----------------------------------------------
// Permet l'affichage de l'image dans une div quand l'utilisateur la choisi sur sa machine
//-----------------------------------------------
function readURL(input) {
  if (input.files && input.files[0]) {

   size = input.files[0].size;

   if(size > 2000000){
    file = null;
    $('#render').css({'background':''});
    $('#render,.supprAddedPic,.ctnForm').fadeOut();
    return;

  }
  var reader = new FileReader();

  reader.onload = function (e) {
            //$('#render').attr('src', e.target.result);
            $('#render').css({'background':'url('+e.target.result+')center center'});
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $("body").on('change','#imgInp',function(){
        $('#render,.supprAddedPic').fadeIn();
        $('.blocMessage input[type="text"], .blocMessage button[type="submit"]').fadeIn();
        readURL(this);
      });

//-----------------------------------------------
// Permet l'affichage de l'image dans une div quand l'utilisateur la choisi sur sa machine
//-----------------------------------------------
function readURLPp(input) {
  if (input.files && input.files[0]) {

   size = input.files[0].size;

   if(size > 2000000){
    file = null;
    $('#renderProfil').css({'background':''});
    $('#renderProfil,.supprAddedPicProfil').fadeOut();
    return;

  }
  var reader = new FileReader();

  reader.onload = function (e) {
            //$('#render').attr('src', e.target.result);
            $('#renderProfil').css({'background':'url('+e.target.result+')center center'});
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $("body").on('change','#imgProfil',function(){
        $('#renderProfil,.supprAddedPicProfil').fadeIn();
        
        readURLPp(this);
      });

//-----------------------------------------------
// Action lorsque l'utilisateur clique sur l'icone pour voir sa messagerie
//-----------------------------------------------

$('body').on('click','.showMessages, .notifsMessages', function(){
  $('body,html,*').unmousewheel();
  init = 0;
  document.title = " Streetzer | Home ";
  $('.posts').html("");  
  $('.detailsProfil').remove();
  $('.tbord_container,.description,.deleteSolo,.containerNotifs').remove();
  $('.borderRed').remove();
  $('.posts').css({'margin':'0'});
  $('.front_scroller').css('width','50%').append('<div class="borderRed"></div>');

  Messagerie();

})

/*
      $('body').on('click','.notifs', function(){
       
        horizontalScroll();
        loadWall('notifs','','0',1);
        
        $.ajax({
          url: '../resources/dispatcher.php',
          type: 'POST'
,          data: {clas: 'utilisateur',method: 'supprPostNotifs'},
        }).done(function(){

          isNewStuffOnMyPosts();
        })
        
      })
*/    
  //-----------------------------------------------
// Action lorsque l'utilisateur clique sur l'icone pour voir ses notifications
//-----------------------------------------------
$('body').on('click','.notifs', function(){

  $('body,html,*').unmousewheel();
  init = 0;
  $('.containerNotifs').html("");
  $('.posts').html("");  
  $('.detailsProfil,.borderRed,.ppic,.addamis,.suppramis,.description,.deleteSolo,.containerNotifs').remove();
  $('.tbord_container').remove();

  $('.front_scroller').append('<div class="containerNotifs"><ul></ul></div>');

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'JSON',
    data: {clas: 'utilisateur',method: 'getNotifs'},
    success: function(data){ 


      for(i=0;i<data.length;i++){
        u = "";
        t = "";
        switch(data[i].type){
          case "newFriend":
          u = "1";
          t = "viens de vous suivre";

          break;
          case "newLike":

          t = "a aimé votre post";

          break;
          case "newComm":
          t =  "a laissé un nouveau commentaire";
          break;

        }
        if(u != "1"){

         $('.containerNotifs ul').append('<li><div class="lineNotifs"></div><div class="cnt"><div data-id="'+data[i].utilisateurs_id_action+'"class="ppNotifs" style="background: url(../images/'+data[i].c1+'/profil_pic/pp.jpg)no-repeat center center;"></div><div class="infosProfilNotifs"><span>'+data[i].pseudo+'</span> <span>'+t+' </span></div><div data-id="'+data[i].img_id+'" style="background: url(../images/'+data[i].c2+'/post_pic/'+data[i].img_id+'.jpg)center center" class="notifsPostsLink"></div><span style="color:#9da1a9;font-size:12px;float: right;margin: 39px 10px 0 0;"> '+data[i].stamp+'</span><div class="clearfix"></div></div> </li>');

       }
       else{

         $('.containerNotifs ul').append('<li><div class="lineNotifs"></div><div class="cnt"><div data-id="'+data[i].utilisateurs_id_action+'"class="ppNotifs" style="background: url(../images/'+data[i].c1+'/profil_pic/pp.jpg)no-repeat center center;"></div><div class="infosProfilNotifs"><span>'+data[i].pseudo+'</span> <span>'+t+' </span></div><span style="color:#9da1a9;font-size:12px;float: right;margin: 39px 10px 0 0;"> '+data[i].stamp+'</span><div class="clearfix"></div></div> </li>');
       }

       $.ajax({
        url: '../resources/dispatcher.php',
        type: 'POST',
        data: {clas: 'utilisateur',method: 'supprPostNotifs'},
      }).done(function(){

        isNewStuffOnMyPosts();
      })


    }



  },
  error: function(error){

    console.log(error);
  },
})
$('body').on('click','.ppNotifs',function(){
  idUser = $(this).data('id');
  loadWall('profile',idUser,'0',1,"");

})

        /*
        $.ajax({
          url: '../resources/dispatcher.php',
          type: 'POST',
          data: {clas: 'utilisateur',method: 'supprPostNotifs'},
        }).done(function(){

          isNewStuffOnMyPosts();
        })
*/
})
$('body').on('click','.notifsPostsLink',function(){

  var postId = $(this).data('id');
  displayPic(postId);
})
$('body').on('click','.tableau_bord', function(){
  $('html,body,*').unmousewheel();
  init = 0;
  $('.posts').html(""); 
  $('.tbord_container').remove();
  $('.front_scroller').css({'width':'600px'}); 
  $('.detailsProfil,.borderRed,.ppic,.addamis,.suppramis,.description').remove();


  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    async: false,
    data: {clas: 'utilisateur',method: 'getUtilisateur',paramsClass: JSON.stringify(),params: JSON.stringify(params)},
  })
  .done(function(data) {
    console.log(data);
    for(i=0;i<data.length;i++){
      ppic = data[i].c1;
      switch(data[i].vehicule){

        case "":
        var selectvehicule = '<select class="typevehicule" name="typevehicule"><option value="">Choisissez votre véhciule</option><option value="voiture">Voiture</option><option value="moto">Moto</option></select> ';
        break;
        case "voiture":
        var selectvehicule = '<select class="typevehicule" name="typevehicule"><option value="voiture">Voiture</option><option value="moto">Moto</option></select> ';
        break;

        case "moto":
        var selectvehicule = '<select class="typevehicule" name="typevehicule"><option value="moto">Moto</option><option value="voiture">Voiture</option></select> ';
        break;
      }
      switch(data[i].sexe){

        case "":
        var selectsexe = '<select class="sexe" name="sexe"><option value="">Votre sexe...</option><option value="male">Homme</option><option value="female">Femme</option></select> ';
        break;
        case "male":
        var selectsexe = '<select class="sexe" name="sexe"><option value="male">Homme</option><option value="female">Femme</option></select> ';
        break;

        case "female":
        var selectsexe = '<select class="sexe" name="typevehicule"><option value="female">Femme</option><option value="male">Homme</option></select> ';
        break;
      }
      if(data[i].description == "Cet utilisateur n'a pas encore rentré de description"){

        data[i].description = "";
      }
      $('.front_scroller').append('<div class="tbord_container"><p class="changepp">Cliquez dessus pour modifier la photo</p><div class="modif_pp"><div style="background: url(../images/'+data[i].c1+'/profil_pic/pp.jpg)center center;background-size:cover;  " class="blocChangeProfil"><div class="form"><output style="position: absolute; margin-left: 10px;"" id="filesInfo"></output><div id="jti"  style="width:468px; height:30%;margin: 0 0 0 9px; overflow:hidden; background-repeat: no-repeat;""><div class="containSpinnerProfil"><div class="spinnerProfil"></div></div><input type="file" id="imgProfil" accept="image/*"" name="postImgProfil" style="font-size:600px; opacity:0; -moz-opacity:0; filter:alpha(opacity=0); float:right; cursor:pointer" /><output id="filesInfo"></output><iframe id="ifr1" name="send" style="width:1px; height:1px;""></iframe></div><img id="renderProfil" src="../layout/img/pisdead.png" alt="your image" /><div class="supprAddedPicProfil"></div><div class="clearfix"></div></div></div></div><div class="modif_info_container"><h1>Modification du profil</h1><div class="containerInputProfil"><label>Prénom</label><input class="prenom" placeholder="Prénom" value="'+data[i].prenom+'"/></div><div class="containerInputProfil"><label>Nom</label><input  class="nom" placeholder="Nom" value="'+data[i].nom+'"/></div><div class="containerInputProfil"><label>Ville</label><div class="ui-widget"><input class="ville" placeholder="Ville" value="'+data[i].ville+'"/></div><div class="containerInputProfil"><label>Date de naissance</label><input class="naissance" placeholder="Date de naissance" value="'+data[i].naissance+'"/></div><div class="containerInputProfil"><label>Mail</label><input class="email" value="'+data[i].email+'" placeholder="Email"/></div><div class="containerInputProfil"><label>Pseudo</label><input class="pseudoMod" placeholder="Pseudo" value="'+data[i].pseudo+'"/></div><div class="containerInputProfil"><label>Véhicule</label>'+selectvehicule+'</div><div class="containerInputProfil"><label>Marque</label><input class="marque" type="text" placeholder="Marque du véhicule..." value="'+data[i].marque_vehicule+'"/></div><div class="containerInputProfil"><label>Sexe</label>'+selectsexe+'</div><div class="containerInputProfil"><label>Description</label><textarea class="descriptionModif">'+data[i].description+'</textarea></div><br/><div class="containerInputProfil"><button class="updateProfil">Mettre à jour le profil</button></div></div></div>');
    }

  })
.fail(function(fail) {
  console.log(fail);
})


})

$('body').on('click','.updateProfil',function(e){

 var check = 0;
 e.preventDefault();

 var nom = $('.nom').val();
 var prenom = $('.prenom').val();
 var email = $('.email').val();
 var pseudo = $('.pseudoMod').val();
 var ville = $('.ville').val();
 var naissance = $('.naissance').val();
 var typevehicule = $('.typevehicule').val();
 var marque = $('.marque').val();
 var sexe  = $('.sexe').val();
 var description = $('.descriptionModif').val();

 if($.trim($('.nom').val().length) == 0){
  check++;
  $('.nom').val("");
  $('.nom').val("Nom obligatoire");
  $('.nom').css({'color':'red','border':' 1px solid red'});
}
if($.trim($('.prenom').val().length) == 0){
  check++;
  $('.prenom').val("");
  $('.prenom').val("Prénom obligatoire");
  $('.prenom').css({'color':'red','border':' 1px solid red'});

}
if($.trim($('.email').val().length) == 0){
 check++;
 $('.email').val("");
 $('.email').val("Email obligatoire");
 $('.email').css({'color':'red','border':' 1px solid red'});

}
if($.trim($('.email').val().match(/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/))){

}
else{
  check++;
  $('.email').val("");
  $('.email').val("Mail incorrect");
  $('.email').css({'color':'red','border':' 1px solid red'});
}
if($.trim($('.pseudoMod').val().length) == 0){
 check++;
 $('.pseudo').val("");
 $('.pseudo').val("Pseudo obligatoire");
 $('.pseudo').css({'color':'red','border':' 1px solid red'});
}

if($.trim($('.ville').val().length) == 0){
  check++;
  $('.ville').val("");
  $('.ville').css({'color':'red','border':' 1px solid red'});

}

if($.trim($('.naissance').val().length) != 0){

  if($.trim($('.naissance').val().match(/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/))){

  }else{
    check++;
    $('.naissance').val("");
    $('.naissance').val("Mauvais format date de naissance ( dd/mm/aaaa)");
    $('.naissance').css({'color':'red','border':' 1px solid red'});
  }

}




if($.trim($('.typevehicule').val().length) == 0){
  check++;
  $('.typevehicule').css({'color':'red','border':' 1px solid red'});


}
if($('.typevehicule ').val() == "voiture" || "moto"){

}
else{
 check++;
 $('.typevehicule').css({'color':'red','border':' 1px solid red'});  
}



if(check != 0){

  return;
}
var paramsClass = [nom,prenom,email,pseudo];
var params = [typevehicule,ville,sexe,marque,naissance,description,check];

$.ajax({
  url: '../resources/dispatcher.php',
  type: 'POST',
  data: {clas: 'utilisateur',method: 'updateUtilisateur', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
})
.done(function(data) {
  console.log(data);
  location.reload();
})
.fail(function(fail) {
  console.log(fail);
})



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

  horizontalScroll();
  init = 1;
  $('.friendlist,.description,.borderRed,.ppic,.addamis,.suppramis,.deleteSolo,.containerNotifs').remove();  
  $('.tbord_container').remove();  
  friendsList();

})

     /* $('body').on('click', '#comments', function (e){

        
        var idPost = $(this).data('genc');
        var idClass = $(this).data('id');
        console.log(idClass);   
        console.log(idPost);   
        $('.gen'+idClass+' .commentsPlace').fadeIn(500);
        $('.gen'+idClass+' .commentsPlace').animate({'top':'0'},500);
        $('.listComments ul').html("");
        getComments(idPost, idClass);
        



      })
*/

$('body').on('click', '.showProfile', function (e){


  var idUser = $(this).data('idu');
  $('.listComments ul').html("");
  loadWall('profile',idUser,'0',1,"");



})

//---------------------------------------------
// Gestion d'ajout et de supression d'un amis
//---------------------------------------------
$('body').on('click', '.addamis', function (e){


  var idUser = $(this).parent().find('.profilNP').data('id');


  $('.listComments ul').html("");

  e = $(this);
  paramsClass = [doctype];
  params = [idUser];

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'amis',method: 'ajoutAmis', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  }).done(function(data){
    console.log(data);
    $(e).removeClass('addamis').addClass('suppramis');
  })        
})

$('body').on('click', '.suppramis', function (e){


  var idUser = $(this).parent().find('.profilNP').data('id');
  console.log(idUser);
  e = $(this);

  $('.listComments ul').html("");

  paramsClass = [doctype];
  params = [idUser];
  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    data: {clas: 'amis',method: 'suppramis', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  }).done(function(){

    $(e).removeClass('suppramis').addClass('addamis');
  })

})

$('body').on('keypress', '.contentCommTs', function (e){

  e = e || window.event;
  if (e.keyCode == 13)  {

    var idu = $(this).parent().parent().find('.soloPhotoProfil').data('id');
    var pclass = $(this).data('genc');
    var idPost = $(this).data('id');
    idClass = $(this).data('genc');
    var contenuComm = $(this).val();
    $(this).val(" ");


    $('.listComments ul').html("");
    var params = [contenuComm,idPost,idu];

    $.ajax({
      url: '../resources/dispatcher.php',
      type: 'POST',
      data: {clas: 'commentaires',method: 'addComments',params: JSON.stringify(params)},
    })
    .done(function(data) {
      console.log(data);  
      if(data == "spam"){

        alert('Veuillez attendre 120 secondes avant de poster un autre commentaire.');
      }
      getComments(idPost, idClass);
      loadCommentsOnSolo(idPost);
      $('.toggleInput'+c+'').animate({'right':'0'}, 1000);
      $('.gen'+pclass+' .commentsPlace').fadeIn(500);
      $('.gen'+pclass+' .commentsPlace').animate({'top':'0'},500);



    })
  }
})



//--------------------------------------------
//Je fais apparaitre l'overlay qui presente le coeur et le bouton commentaire
//--------------------------------------------
$('body').on({
  mouseenter: function () {


    horizontalScroll();
    init = 1;
    /*
   $(this).find('.frontContent').hide();
   $(this).find('.hov').show();
   */
   $(this).find('.frontContent').stop().fadeOut(200);
   $(this).find('.hov').stop().fadeIn(200);
   cible = $(this);
   var postId = $(this).find('.contentCommTs').data('id');
   var uid = $(this).find('.showProfile').data('idu');



   paramsClass = [postId,doctype];

       //--------------------------------------------
       //Je recupere le nombre de commentaire pour l'afficher dans la div commentaires
       //--------------------------------------------
       $.ajax({
         url: '../resources/dispatcher.php',
         type: 'POST',
         data: {clas: 'wall',method: 'filterLike', paramsClass: JSON.stringify(paramsClass),params: ""},
         async: false,
       })
       .done(function(data) {

        if($.trim(data) == "pinned"){

          cible.find('.stick').addClass('pinned').css({'background':'rgba(238,65,47,0.6)'});
          
        }

      })

       if($(this).hasClass('unchecked')){

         counter('comments',$(this),postId);
         counter('coeur',$(this),postId);

       }

     },
     mouseleave: function () {
       horizontalScroll();
       init = 1;
        /*
       $(this).find('.frontContent').show();
       $(this).find('.hov').hide();
       */
       $(this).find('.frontContent').fadeIn(200);
       $(this).find('.hov').fadeOut(200);
       $('.commentsPlace').stop().animate({'top':'100%'}, 300);
       $('.showPostInput').stop().animate({'right':'0'}, 300);
       //$('.containerPicto').animate({'right':'-45px'},10);
       //$('.infoPost').animate({'bottom':'-35px'},10);

     }
   }, ".postContainer");


//---------------------------------------------
//Au clic, le post est ajouté sur le streetwall
//--------------------------------------------
$('body').on('click', '.stick', function (e){
  var idu = $(this).parent().parent().find('.showProfile').data('idu');
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
  params = [type,idu];

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',

    data: {clas: 'wall',method: 'filterLike', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
  })
  .done(function(data) {


    if($.trim(data) == "pinnedok"){
      $(ctn).removeClass('b').addClass('a').removeClass('pinned').addClass('unpinned').css({'background':'rgba(0,0,0,0.6)'});
      if($(cible).parent().data('type') == "wall"){

        $(cible).parent().fadeOut(function(){

          $(this).remove();

        })
      }
    }
    if($.trim(data) == "unpinned"){
      $(ctn).removeClass('unpinned').addClass('pinned').css({'background':'rgba(238,65,47,0.6)'});
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

  $(this).removeClass('newConv');
  isNewMessages();
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
//Je ping pour dire que je suis en ligne
// N'est plus utilisé
//------------------------------------------------------------------------
/*setInterval(function() {
  $.ajax({
    url: '../resources/gestion_messagerie/ping.php', 
  })
  .done(function() {

  })
}, 100000);
*/

//------------------------------------------------------------------------
//Je clean ma table online pour n'avoir que les utilisateurs vraiment en ligne
// N'est plus utilisé
//------------------------------------------------------------------------
/*setInterval(function() {
  $.ajax({
    url: '../resources/gestion_messagerie/timeoutcompare.php', 
  })
  .done(function() {

  })
}, 100000);
-9


//------------------------------------------------------------------------
/*je rafraichis mes utilisateurs en ligne toute les 30 secondes
setInterval(function() {

  $('.listeInsideOnline').html("");
  Messagerie();

}, 30000);
*/





//------------------------------------------------------------------------

//------------------------------------------------------------------------
// Scroll inverse et loadMore
//------------------------------------------------------------------------






//------------------------------------------------------------------------
// J'affiche l'image choisi par l'utilisateur dans la div aprés qu'il ai choisi ce qu'il veux envoyer , et filtre sa taille
//------------------------------------------------------------------------

$("body").on("change","#imgInp", function() {




 size = this.files[0].size;

 if(size > 2000000){
  file = null;
  alert('Votre image ne doit pas faire plus de 2MO');
  $('#render').css({'background':''});
  $('#render,.supprAddedPic,.ctnForm').fadeOut();
  return;

}
else{
  file = this.files[0];
  $('#render,.supprAddedPic,.ctnForm').fadeIn();

}




})  
//------------------------------------------------------------------------
// Même chose qu'au dessus, mais pour la photo de profil dans le tableau de bord
//------------------------------------------------------------------------

$("body").on("change","#imgProfil", function() {




 size = this.files[0].size;

 if(size > 2000000){
  file = null;
  alert('Votre image ne doit pas faire plus de 2MO');
  $('#render').css({'background':''});
  $('#render,.supprAddedPicProfil,.ctnForm').fadeOut();
  return;

}
else{
  file = this.files[0];
  $('#renderProfil,.supprAddedPicProfil').fadeIn();

  var formdata = new FormData();    
  var ctn = $(this).val();


  if (formdata) {
    formdata.append("postImgProfil", file);
    formdata.append('clas', 'utilisateur');
    formdata.append('method', 'changeProfilPic');


    jQuery.ajax({
      url: "../resources/dispatcher.php",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,

      beforeSend:function(){

       $('#renderProfil').fadeOut();


       $('.containSpinnerProfil').fadeIn();

     },
     success:function(){

      $('.containSpinnerProfil').fadeOut();
      $('.blocMessage input[type="text"], .blocMessage button[type="submit"]').fadeOut();


      $.when($('.ppBg').remove()).then( $('.overlayPp').append('<div style="background: url(../images/'+ppic+'/profil_pic/pp.jpg)center center; background-size:cover;" class="ppBg"></div>'));


      $.when($('.blocChangeProfil').remove()).then($('.modif_pp').append('<div style="background: url(../images/'+ppic+'/profil_pic/pp.jpg)center center;background-size:cover;  " class="blocChangeProfil"><div class="form"><output style="position: absolute; margin-left: 10px;" "="" id="filesInfo"></output><div id="jti" style="width:468px; height:30%;margin: 0 0 0 9px; overflow:hidden; background-repeat: no-repeat;" "=""><div class="containSpinnerProfil"><div class="spinnerProfil"></div></div><input type="file" id="imgProfil" accept="image/*" "="" name="postImgProfil" style="font-size:600px; opacity:0; -moz-opacity:0; filter:alpha(opacity=0); float:right; cursor:pointer"><output id="filesInfo"></output><iframe id="ifr1" name="send" style="width:1px; height:1px;" "=""></iframe></div><img id="renderProfil" src="../layout/img/pisdead.png" alt="your image"><div class="supprAddedPicProfil"></div><div class="clearfix"></div></div></div>'));

      location.reload();



      formdata = null;

      file = null;

    }

  });
} 


}




})  
//------------------------------------------------------------------------
// J'envoi le formulaire pour créer un nouveau post avec une nouvelle photo
//------------------------------------------------------------------------

$('body').on('keypress','.ctnForm',function(e){
  e = e || window.event;
  if (e.keyCode == 13)  {

    var formdata = new FormData();    
    var ctn = $(this).val();
    paramsClass = [doctype,ctn];
    params = ["std"];
    if (formdata) {
      formdata.append("postImg", file);
      formdata.append('clas', 'post');
      formdata.append('method', 'newPost');
      formdata.append('paramsClass', JSON.stringify(paramsClass));
      formdata.append('params', JSON.stringify(params));
            //params = [formdata];
            jQuery.ajax({
              url: "../resources/dispatcher.php",
              type: "POST",
              data: formdata,
              processData: false,
              contentType: false,
              beforeSend:function(){

               $('#render').fadeOut();
               $('.ctnForm').val('');

               $('.containSpinner').fadeIn();

             },
             success:function(data){
              console.log(data);
              if(data == "spam"){

                alert('Veuillez attendre 120 secondes avant de poster une autre photo.');
              }
              $('.containSpinner').fadeOut();
              $('.blocMessage input[type="text"], .blocMessage button[type="submit"], .supprAddedPic').fadeOut();

              loadWall("world",'',"0",1);
              $('.form').fadeOut(500);
              $('.theChoice').fadeIn(100);
              formdata = null;

              file = null;

            }

          });
          } 
        }
      })
$('.supprAddedPic').click(function(){

  $('#render,.supprAddedPic,.ctnForm').fadeOut();
  $('#render').css({'background-image':'url()'});
  formdata = null;
  file = null;

})
$('body').on('click','.supprAddedPicProfil',function(){

  $('#renderProfil,.supprAddedPicProfil').fadeOut();
  formdata = null;
  file = null;

})
$('body').on('click','.delete',function(){

  var pid = $(this).data('id');
  var uid = $(this).data('gvar');

  paramsClass = [doctype];
  params = [pid,uid];

  var elem = $(this).parent().parent().parent().parent();
  $.ajax({
    url: '../resources/dispatcher.php',
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
    url: '../resources/logout.php',

  })
  .done(function() {

    sessionStorage.clear();
    window.location.href = "../";

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
  cid = $(this).parent().find('.usertag span').data('cid');
  
  var params = [uid,cid];

  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    datatype: 'json',
    data: {clas: 'messagerie',method: 'supprConvers',params: JSON.stringify(params)},
  })
  .done(function(d) {
    console.log(d);
   Messagerie();
 })

  
  

})



$('body').on('keyup','.ville',function(){

  var ctnvar = $(this).val();

  $(".ville").autocomplete({

    minLength: 0,
    scrollHeight: 420, 

    source: function(req, add){

      $.ajax({
        url:'../resources/suggestVille.php',
        type:"post",
        dataType: 'json',
        data: {ville: ctnvar},
        async: true,
        cache: true,
        success: function(data){


          add($.map(data, function(i){  

            if(i.ville_nom_reel != ''){
              return i.ville_nom_reel;
            }



          }));  



        },

      });
    },
    select: function (ui){

      console.log(ui);


    }

  });



})

$('body').on('keyup','.marque',function(){

  var ctnvar = $(this).val();

  $(".marque").autocomplete({

    minLength: 0,
    scrollHeight: 420, 

    source: function(req, add){

      $.ajax({
        url:'../resources/suggestMarque.php',
        type:"post",
        dataType: 'json',
        data: {marque: ctnvar},
        async: true,
        cache: true,
        success: function(data){


          add($.map(data, function(i){  


            return i.marque;


          }));  







        },

      });
    },
    select: function (ui){

      console.log(ui);


    }

  });



})



$('.showPopContent').click(function(){

  $('.popContent').fadeIn(500); 

})
$('.youtubego').click(function(){

  youtubelink = $('.youtubelink').val();
  //var vimeo = youtubelink.match(/player\.vimeo\.com\/video\/([0-9]*)/);

  params = ['youtube',youtubelink];
  $.ajax({
    url: '../resources/dispatcher.php',
    type: 'POST',
    dataType: 'json',
    data: {clas: 'post',method: 'newPost',params: JSON.stringify(params)},
  })
  .done(function(data) {
    console.log(data);
  })
  .fail(function(error) {
    console.log(error);
  })


  

})

$('.yt').keypress(function(e){
  e = e || window.event;
  if (e.keyCode == 13)  {

    e.preventDefault();
    var contenuTxt = $(this).val();


    params = ['youtube',contenuTxt];

    $.ajax({
      url: '../resources/dispatcher.php',
      type: 'POST',
      data: {clas: 'post',method: 'newPost',params: JSON.stringify(params)},
    })
    .done(function(data) {
      console.log(data);
      $('.enterLink').fadeOut(500);
      $('.theChoice').fadeIn(100);
      loadWall('world','',0,1);
      if(data == "spam"){

        alert('Veuillez attendre 120 secondes avant de poster un autre message.');
      }


    }).error(function(err){

      console.log(err);
    })
  }
});


$('.ytb').click(function(){

  $('.theChoice').fadeOut(500);
  $('.enterLink').fadeIn(100);
  $('.enterLink input').show();
})
$('.picture').click(function(){

  $('.theChoice').fadeOut(500);
  $('.form').fadeIn(100);
})


$('body').on('click','.gearSettings',function(){

  $('.more').toggle(200);
})


$('.layoutPetit').click(function(){
  isset = 1;
  boxsize = "petit";
  $('.posts .imgPost').css({'width':'400px!important','height':'32.7%'});
  $('.posts').css({'width':'400px'});
  $('.bigOne').css({'height':'32.7%'});
  $('.contentPost p').css({'font-size':'0.8em'});
  $('.more').toggle();
})

$('.layoutMoyen').click(function(){
  isset = 1;
  boxsize = "moyen";
  $('.posts .imgPost').css({'width':'478px!important','height':'49.5%'});
  $('.posts').css({'width':'478px'});
  $('.bigOne').css({'height':'99.8%'});
  $('.contentPost p').css({'font-size':'1em'});
  $('.more').toggle();
})

$('.layoutGrand').click(function(){
  isset = 1;
  boxsize = "grand";
  w = "650px!important"
  $('.posts .imgPost').css({'width':'650px!important','height':'98%'});
  $('.posts').css({'width':'650px!important'});
  $('.bigOne').css({'height':'99.8%'});
  $('.contentPost p').css({'font-size':'1em'});
  $('.more').toggle();
})



});



/****

Grande divs : $('.posts li').css({'width':'650px!important','height':'99%'});
              $('.posts').css({'width':'650px'});
              $('.bigOne').css({'height':'99.8%'});


Petite divs : $('.posts li').css({'width':'400px!important','height':'32.7%'});
              $('.posts').css({'width':'400px'});
              $('.bigOne').css({'height':'32.7%'});

Standard divs : $('.posts li').css({'width':'478px!important','height':'49.5%'});
                $('.posts').css({'width':'478px'});
                $('.bigOne').css({'height':'99.8%'});

                ****/
