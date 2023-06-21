$(function(){
	$(document).on("click","#order-confirm",function(){
		var content=$("#log-content").val();
		$.post("/moduleadmin.php?m=cy2c_order&a=confirm&ajax=1",{
			orderid:orderid,
			content:content
		},function(res){
			if(res.error){
				skyToast(res.message)
				return false;
			}
			window.location.reload();
		},"json")
	})
	$(document).on("click","#order-cancel",function(){
		var content=$("#log-content").val();
		$.post("/moduleadmin.php?m=cy2c_order&a=cancel&ajax=1",{
			orderid:orderid,
			content:content
		},function(res){
			if(res.error){
				skyToast(res.message)
				return false;
			}
			window.location.reload();
		},"json")
	})
	$(document).on("click","#order-send",function(){
		var content=$("#log-content").val();
		var express_no=$("#express-no").val()
		$.post("/moduleadmin.php?m=cy2c_order&a=send&ajax=1",{
			orderid:orderid,
			content:content,
			express_no:express_no
		},function(res){
			window.location.reload();
		},"json")
	})
	
	$(document).on("click","#order-finish",function(){
		 
		$.post("/moduleadmin.php?m=cy2c_order&a=finish&ajax=1",{
			orderid:orderid
		},function(res){
			if(res.error){
				skyToast(res.message)
				return false;
			}
			window.location.reload();
		},"json")
	})
})