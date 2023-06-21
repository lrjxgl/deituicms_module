 
$(function(){
	$(document).on("click",".js-user-forbid",function(){
		var userid=$(this).attr("userid");
		var gid=$(this).attr("gid");
		skyJs.confirm({
			content:"禁言用户吗？",
			success:function(){
				$.get("/module.php?m=group_user&a=forbid&isforbid=1&ajax=1&userid="+userid+"&gid="+gid,function(data){
					skyJs.toast(data.message);
				},"json")
			}
		})
		
	})
	
	$(document).on("click",".js-user-pass",function(){
		var userid=$(this).attr("userid");
		var gid=$(this).attr("gid");
		$.get("/module.php?m=group_user&a=pass&ajax=1&userid="+userid+"&gid="+gid,function(data){
			skyJs.toast(data.message);
		},"json")
	})
})
