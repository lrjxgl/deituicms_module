$(document).on("click",".js-addMoney",function(){
		var id=$(this).attr("data-id");
		if(confirm("确认增加1元赏金吗")){
			$.get("/module.php?m=paotui&a=addmoney&ajax=1&id="+id,function(data){
				if(data.error){
					skyToast(data.message);
				}else{
					skyToast(data.message);
					$("#sm"+id).html(data.data);
				}
				
				
			},"json")
		}
	})
	
	$(document).on("click",".js-cancel",function(){
		var id=$(this).attr("data-id");
		$(this).remove();
		if(confirm("确认取消任务吗")){
			$.get("/module.php?m=paotui&a=cancel&ajax=1&id="+id,function(data){
				if(data.error){
					skyToast(data.message);
				}else{
					skyToast(data.message);
					$("#sm"+id).html(data.data);
				}
				
				
			},"json")
		}
	})