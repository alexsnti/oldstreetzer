$(document).ready(function(){

$('.mainBloc  input,select').focus(function(){

$(this).css({'border':'1px solid white','color':'black'}).val("");
$('.inscrireSubmit').val("S'incrire");
})
$('.inscrire').click(function(){

  $('body,html').animate({scrollTop:'1000%'},1000);
})



  $('body').on('click','.fbsignup,.logfb',function(){

    

    Login();
  })
	
 window.fbAsyncInit = function() {
    FB.init({
      appId      : '1392215581046118', // Set YOUR APP ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
 
    FB.Event.subscribe('auth.authResponseChange', function(response) 
    {
     if (response.status === 'connected') 
    {
        console.log("Connection");
        //SUCCESS
 
    }    
    else if (response.status === 'not_authorized') 
    {
        console.log("FAILLUUURRREEEE");
        //FAILED

    } else 
    {
        
        console.log("It was weird as fuck.");
        //UNKNOWN ERROR

    }
    }); 
 
    };

    function Login()
    {
 
        FB.login(function(response) {
           if (response.authResponse) 
           {
                loginUser();
            } else 
            {
             console.log('User cancelled login or did not fully authorize.');
            }
         },{scope: 'email,user_photos,user_videos'});
 
    }

    function loginUser() {
          
          FB.api('/me/picture?type=normal', function(response) {
 
          pic = response.data.url
          
            });
          

          FB.api('/me', function(response) {


          tab_donnees = [response.first_name,response.last_name,response.username,response.bio,response.birthday,response.email,response.gender,response.location.name,response.id,pic];
           
            $.ajax({
              url: 'resources/user_facebook.php',
              type: 'POST',
              async: false,
              data: {tab: tab_donnees},
            })
            .done(function(data) {
              
               
              
                location.reload();
              
              if(data === "a"){
                alert("L'adresse mail existe déja.");
              }

            })
            
 
    });
  }



 
  // Load the SDK asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
 

    $('.inscrireSubmit').click(function(e){
      var check = 0;
      e.preventDefault();

      var nom = $('.nom').val();
      var prenom = $('.prenom').val();
      var email = $('.email').val();
      var pseudo = $('.pseudo').val();
      var pass = $('.passinscr').val();
      var passVerif = $('.passverif').val();
      var ville = $('.ville').val();
      var naissance = $('.naissance').val();
      var typevehicule = $('.typevehicule').val();
      var marque = $('.marque').val();
      var sexe  = $('.sexe').val();

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
        if($.trim($('.pseudo').val().length) == 0){
           check++;
            $('.pseudo').val("");
            $('.pseudo').val("Pseudo obligatoire");
            $('.pseudo').css({'color':'red','border':' 1px solid red'});
      }
        if($.trim($('.pass').val().length) == 0 || $.trim($('.pass').val().length) < 6  ){
            check++;
            $('.pass,.passverif').val("");
            $('.pass').val("Mini 8 caractères");
            $('.pass').css({'color':'red','border':' 1px solid red'});

      }
      if( $('.passinscr').val() != $('.passverif').val()  ){
            check++;
            $('.passinscr').val("");
            $('.passverif').css({'color':'red','border':' 1px solid red'});


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
    

      var paramsClass = [nom,prenom,email,pseudo,pass,passVerif];
      var params = [typevehicule,ville,sexe,marque,naissance,check];

      $.ajax({
        url: 'resources/dispatcher.php',
        type: 'POST',
        data: {clas: 'utilisateur',method: 'NewUtilisateur', paramsClass: JSON.stringify(paramsClass),params: JSON.stringify(params)},
      })
      .done(function(data) {
       $('.containForm').remove();
       $('.mainBloc').append('<h1>Merci de votre inscription ! un email de confirmation vous a été envoyé, vous allez être redirigé...');
       setTimeout(function(){
        window.location.href = "home";
      },3000);
       
      }).fail(function(fail){

        console.log(fail);
      })


});
      
    
      


  

  $('body').on('keyup','.ville',function(){
    
    var ctnvar = $(this).val();

      $(".ville").autocomplete({

        minLength: 0,
        scrollHeight: 420, 

        source: function(req, add){

          $.ajax({
            url:'resources/suggestVille.php',
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
            url:'resources/suggestMarque.php',
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


})

