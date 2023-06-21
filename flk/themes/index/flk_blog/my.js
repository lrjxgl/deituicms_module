var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
 
		goBlog:function(id){
			window.location="/module.php?m=flk_blog&a=show&id="+id;
		},
		del:function(id){
			var that=this;
			if(confirm("删除后不可恢复，确认删除吗？")){
				$.ajax({
					url:"/module.php?m=flk_blog&a=delete&ajax=1&id="+id,
				
					dataType:"json",
					success:function(res){
						var list=that.pageData.list;
						var newlist=[];
						for(var i=0;i<list.length;i++){
							if(list[i].id!=id){
								newlist.push(list[i]);
							}
						}
						that.pageData.list=newlist;
						 
					}
				})
			}
			
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_blog&a=my&ajax=1",
				
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		}
	}
})