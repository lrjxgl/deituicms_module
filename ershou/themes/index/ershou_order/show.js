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
$(document).on("click","#order-cancel-submit",function(){
	if(confirm("确认取消订单?")){
		$.get("/module.php?m=ershou_order&a=cancel&ajax=1&orderid="+orderid,function(res){
			
			if(!res.error){
				window.location.reload();
			}
		},"json")
	}
})
$(document).on("click","#order-receive-submit",function(){
	$.get("/module.php?m=ershou_order&a=receive&ajax=1&orderid="+orderid,function(res){
		if(res.error){
			skyToast(res.message)
			return false;
		}
		window.location.reload();
	},"json")
})

$(document).on("click","#goPay",function(){
	 
	$.ajax({
		url:"/module.php?m=ershou_order&a=pay&ajax=1&orderid="+orderid,
		dataType:"json",
		success:function(res){
			if(res.error){
				skyToast(res.message);
				return false;
			}
			window.location=res.data.payurl;
		}
	})
})

$(document).on("click","#express-search",function(){
	var express_no=$(this).attr("v");
	var iframe='<iframe style="width:100%;height:400px;border:0;" src="http://m.kuaidi100.com/result.jsp?nu='+express_no+'"></iframe>';
	var html='<div class="modal-mask"></div><div class="modal">'+iframe+'</div>';
	if($("#expressWeb").length>0){
		$("#expressWeb").html(html);
	}else{
		html='<div id="expressWeb" class="modal-group">'+html+'</div>';
		$("body").append(html);
	}
	
	$("#expressWeb").show();
})