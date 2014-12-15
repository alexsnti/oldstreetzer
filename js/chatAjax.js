

var offsetInc = 0;


//------------------------------------------------------------------------
//------------------------------------------------------------------------
// Je lance la création de mes fenetres de chat 
$('body').on('click', '.usertag', function (e){

    //Je change la classe pour plus que l'element soit cliquable si il est déja déployé
    $(this).removeClass('usertag').addClass('displayedChat');
    var elt = $(this);
    var iduDistant = $(this).find('span').attr('id');
    console.log(iduDistant);
    var element = $('.stackChat');
    
    e.preventDefault(e)
    var nameContact = $(this).text();
    console.log(nameContact);
    var calcOffset = (205 * offsetInc);

    var classWithOffset = "fenetrechat"+offsetInc;
    var data = $(this).html();
      console.log(offsetInc);  

  //je recupere l'ensemble des messages

 $('.'+classWithOffset+' .contentConvers').html('');
        $.ajax({
        url: '../resources/gestion_messagerie/get_messages.php',
        type: 'POST',
        dataType: 'json',
        data: {idDist: iduDistant},
    })
    .done(function(msg) {
      

     for(i=0;i<msg.length;i++){
      
        if(msg[i].utilisateurs_id_distant ==  iduDistant){
            $('.'+classWithOffset+' .contentConvers').append('<li class="liDis"><p><span ">'+msg[i].contenu+'</span></li>');
        }
        else{0

            $('.'+classWithOffset+' .contentConvers').append('<li class="liCurr"><p><span ">'+msg[i].contenu+'</span></p></li>');
        }


        
     }
       var topHeight = $('.contentConvers').prop('scrollHeight');
            $('.contentConvers').animate({scrollTop:topHeight},'slow');  

        console.log(topHeight);

     //----------
     //Je recupere les derniers messages
setInterval(function(){

        $.ajax({
            url: '../resources/gestion_messagerie/get_last_messages.php',
            type: 'POST',
            dataType: 'json',
            data: {idDist: iduDistant},
            
        })
        .done(function(pingback) {
                   
            for(i=0;i<pingback.length;i++){
      
        if(pingback[i].utilisateurs_id_distant ==  iduDistant){
          $('.'+classWithOffset+' .contentConvers').append('<li class="liDis"><p><span ">'+pingback[i].contenu+'</span></li>');
        }
        else{

            $('.'+classWithOffset+' .contentConvers').append('<li class="liCurr"><p><span ">'+pingback[i].contenu+'</span></p></li>');
        }
      

       
        
     }
       if(pingback.length != 0){
            topHeight = $('.contentConvers').prop('scrollHeight');
            $('.contentConvers').animate({scrollTop:topHeight},'slow'); 
            console.log('2ndTOP');
            console.log(topHeight);
        }

        }).fail(function(faaail){

            console.log(faaail);
        });
},100)
 


        
      
    }).fail(function(fail){

    console.log(fail);
});
 

        //------------------------------------------------------------------------        
                        //J'instancie mon stack de conversation
                     
                      
                        //------------------------------------------------------------------------
                        //Je crée ma fenetre de chat
                        if(!element.length){

                        $('.masterContainerConvers').append('<div class="containerConvers '+classWithOffset+'"><h1>'+nameContact+'</h1><div class="contentConvers"><ul></ul></div><div class="formSendMess"><input id="'+iduDistant+'" type="text" class="msgbox'+classWithOffset+'" name="message"></div></div>');

                        //$('.msgbox'+classWithOffset+'').focus();
                       
                      
                                    
                        $('.msgbox'+classWithOffset+'').keypress(function(event){

                         if (event.keyCode == 13)  {
                        event.preventDefault();
                        var idAenvoyer = $(this).attr("id");
                        var contenuTxt = $(this).val();

                        $.ajax({
                            url: '../resources/gestion_messagerie/ajout_message.php',
                            type: 'POST',
                            data: {idDist: idAenvoyer,cnt: contenuTxt},
                        })
                        .done(function(data) {
                            console.log(data);
                              $('.msgbox'+classWithOffset+'').val("");
                        })
   
                        
                        }
                    });
                        
                        //------------------------------------------------------------------------
                        
                        } 


                      

                  

                         if(!element.length && offsetInc == 3){

                            $('.masterContainerConvers').append('<div class="stackChat"><p>Conv(num)</p></div>');     
                            $('.stackChat').append("<div class='insideStack'><ul></ul></div>");
                                                          }
                        if(element.length){
                           
                            $('.insideStack ul').append('<li><div class="containerConvers '+classWithOffset+' instack"><h1 class="chatHconvers">'+nameContact+'</h1><div class="contentConvers"></div></div></li>');

                            $('.'+classWithOffset+'').css({'width':'100px','height':'30px','position':'relative','float':'none','display':'block'});
                            return;
                        }
                        else{

                               
                        }

                        offsetInc++;

                        });
                            $('body').on('click', '.stackChat', function (e){
                            $('.insideStack').css({'display':'block'});
                            var stackContentCount = 1;
                            var AightStack = $('.instack').height();
                            var convertisationDeLaMort = parseInt(AightStack);

                            $('.instack').each(function(){

                            stackContentCount++;
                            })

                            var CountCalc =  AightStack * stackContentCount;
                            var marginCalc = stackContentCount * stackContentCount;
                                $(this).css({'height':+CountCalc+'px','width':'100px','margin-top':+marginCalc+'px'});
                            })




