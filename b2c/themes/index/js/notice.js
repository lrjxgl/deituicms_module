$(function(){
	$.get("/module.php?m=b2b_msg&a=GetAllNum&ajax=1",function(res){
		var html='';
		if(res.error){
			
			 $("#ft-msg-num").html(0).hide(); 
		}else{
			 if(res.data.num>0){
				 $("#ft-msg-num").html(res.data.num).show();
			 }else{
				 $("#ft-msg-num").html(0).hide(); 
			 }
		}
		 
	},"json")
})
