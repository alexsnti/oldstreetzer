function loadallUsers (){

	$.ajax({
	url: 'i/alluser.php',
	type: 'POST',
	dataType: 'json',
	async: false
	
})
.done(function(dataUser) {
	console.log(dataUser.length);
	$('.containerInfos').html("");
	for(i=0;i<dataUser.length;i++){
		
		
		$('.containerInfos').append('<ul class="list-inline"><li style="width:200px;height:250px;float:left;"><div style="background:url(../images/'+dataUser[i].c1+'/profil_pic/pp.jpg)center center;width:200px;height:150px;background-size:cover;"></div><p>User id: '+dataUser[i].id+'<br> User :'+dataUser[i].nom+' '+dataUser[i].prenom+'<br><button type="button" data-id="'+dataUser[i].id+'" class="lookuser btn btn-default">Voir détail</button><button type="button" class="suppruser btn btn-danger" data-id="'+dataUser[i].id+'">Supprimer</button></p>   ');
	}
	
})
}
function loadallPosts (){

	$.ajax({
	url: 'i/allposts.php',
	type: 'POST',
	dataType: 'json',
	async: false
	
})
.done(function(data) {
	$('.containerInfos').html("");
	for(i=0;i<data.length;i++){

		$('.containerInfos').append('<ul class="list-inline"><li style="width:200px;height:250px;float:left;"><div style="background:url(../'+data[i].root+')center center;width:200px;height:150px;background-size:cover;"></div><p>Post id: '+data[i].img_id+'<br> Auteur :'+data[i].nom+' '+data[i].prenom+'<br><button type="button" class="btn btn-default">Voir détail</button><button type="button" class="btn btn-danger supprPost" data-id="'+data[i].img_id+'">Supprimer</button></p>   ');
	}
})

}

$(document).ready(function(){

loadallPosts();


$('.allposts').click(function(){

	loadallPosts();


})
$('.allusers').click(function(){

	loadallUsers();
})



$('body').on('click','.suppruser',function(){
	
	confirm('Vraiment ?');
	elem = $(this);
	var iduser = $(this).data('id');
	$.ajax({
		url: 'i/deleteUser.php',
		type: 'POST',
		data: {iduser: iduser},
	})
	.done(function(data) {
		elem.parent().parent().remove();
		console.log(data);
	})
	.fail(function(fail) {
		console.log(fail);
	})

})

$('body').on('click','.supprPost',function(){
	
	confirm('Vraiment ?');
	elem = $(this);
	var iduser = $(this).data('id');
	$.ajax({
		url: 'i/deletePost.php',
		type: 'POST',
		data: {iduser: iduser},
	})
	.done(function(data) {
		elem.parent().parent().remove();
	})
	.fail(function(fail) {
		console.log(fail);
	})

})

$('body').on('click','.lookuser',function(){

$('.containerInfos').html("");
var id = $(this).data('id');

$.ajax({
	url: 'i/getUser.php',
	type: 'POST',
	dataType: 'json',
	data: {id: id},
})
.done(function(data) {


for(i=0;i<data.length;i++){

	$('.containerInfos').append('<img style="width:200px;" src="../images/'+data[i].c1+'/profil_pic/pp.jpg"><ul><li>'+data[i].nom+'</li><li>'+data[i].prenom+'</li><li>'+data[i].pseudo+'</li><li>'+data[i].description+'</li><li><button class="connectas btn btn-info" data-id="'+data[i].id+'">Se connecter en tant que '+data[i].nom+' '+data[i].prenom+' </button></li></ul>    ');

	}

})

})

$('body').on('click','.connectas',function(){


var id = $(this).data('id');

$.ajax({
	url: 'i/connectAs.php',
	type: 'POST',
	data: {id: id},
})
.done(function(data) {


	window.open('../home');

})

})



})