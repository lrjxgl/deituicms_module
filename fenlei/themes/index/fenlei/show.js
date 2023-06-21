$(function(){
	$(document).on("click","#adminRecommend",function(){
		 
		skyJs.confirm({
			title:"操作提示",
			content:"确认推荐文章吗",
			success:function(res){
				$.ajax({
					url:"/module.php?m=fenlei&a=recommend&ajax=1",
					dataType:"json",
					data:{
						id:id	
					},
					success:function(res){
						skyJs.toast(res.message);
						if(res.error){
							
							return false;
						}
						if(res.data==1){
							$(".adminRecommend-icon").removeClass("no").addClass("yes")
						}else{
							$(".adminRecommend-icon").removeClass("yes").addClass("no")
						}
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
					url:"/module.php?m=fenlei&a=delete&ajax=1",
					dataType:"json",
					data:{
						id:id	
					},
					success:function(res){
						skyJs.toast(res.message);
						if(res.error){
							
							return false;
						}
						goBack();
					}
				})
			}
		})
	})
})