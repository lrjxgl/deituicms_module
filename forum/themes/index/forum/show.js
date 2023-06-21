$(function(){
	$(document).on("click","#adminRecommend",function(){
		 
		skyJs.confirm({
			title:"操作提示",
			content:"确认推荐文章吗",
			success:function(res){
				$.ajax({
					url:"/module.php?m=forum&a=recommend&ajax=1",
					dataType:"json",
					data:{
						id:id	
					},
					success:function(res){
						if(res.error){
							skyJs.toast(res.message);
							return false;
						}
						window.location.reload();
					}
				})
			}
		})
	})
	
	$(document).on("click","#adminDel",function(){
		 
		skyJs.confirm({
			title:"操作提示",
			content:"确认删除文章吗",
			success:function(res){
				$.ajax({
					url:"/module.php?m=forum&a=delete&ajax=1",
					dataType:"json",
					data:{
						id:id	
					},
					success:function(res){
						if(res.error){
							skyJs.toast(res.message);
							return false;
						}
						goBack();
					}
				})
			}
		})
	})
})