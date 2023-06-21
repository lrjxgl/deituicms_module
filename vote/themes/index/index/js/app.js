// JavaScript Document

function vote_vote(vid,id){
	$.get("/module.php?m=vote_join&a=vote&ajax=1&vid="+vid+"&id="+id,function(data){
		skyToast(data.message);
	},"json");
}

$(function(){
	
	$(document).on("click","#vote_vote",function(){
		 
	});
});