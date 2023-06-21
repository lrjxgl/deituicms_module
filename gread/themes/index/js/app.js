
$(function(){
	$(document).on("click",".js-user-forbid",function(){
		var userid=$(this).attr("userid");
		var gid=$(this).attr("gid");
		$.get("/module.php?m=group_user&a=forbid&isforbid=1&ajax=1&userid="+userid+"&gid="+gid,function(data){
			mui.toast(data.message);
		},"json")
	})
	
	$(document).on("click",".js-user-pass",function(){
		var userid=$(this).attr("userid");
		var gid=$(this).attr("gid");
		$.get("/module.php?m=group_user&a=pass&ajax=1&userid="+userid+"&gid="+gid,function(data){
			mui.toast(data.message);
		},"json")
	})
})
