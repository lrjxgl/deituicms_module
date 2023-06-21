
timeout($(".js-timeout"))
function timeout(obj){
	 
	etime--;
	var s="";
	if(etime>60){
		var i=parseInt(etime/60);
		s+=i+"分";
		s+=(etime-i*60)+"秒"
	}else{
		s+=etime+"秒";
	} 
	obj.html(s)
	
	setTimeout(function(){
		
		timeout(obj);
	},1000)
}

$(document).on("click",".js-show-buy",function(){
	$("#buyBox").show();
})

$(document).on("click",".js-buy-submit",function(){
		var nickname=$("#addr-nickname").val();
		var telephone=$("#addr-telephone").val();
		var address=$("#addr-address").val(); 
		$.post("/module.php?m=car_order&a=order&ajax=1",{
			productid:productid,
			nickname:nickname,
			telephone:telephone,
			address:address
			 
		},function(data){
			skyToast(data.message);
			if(data.error==1000){
				golink("/index.php?m=login");
				return false;
			}else if(data.error){
				return false;
			}
			$("#buyBox").hide();
			if(data.data.gopay){
				window.location=data.data.payurl;
			}else{
				setTimeout(function(){
					window.location.reload();
				},1000)
				
			}
			
			
		},"json")
	})