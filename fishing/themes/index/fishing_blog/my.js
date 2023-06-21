var app=new Vue({
	el:"#App",
	data:function(){
		return {
			 
			blogList:[],
			 
		}
	},
	created:function(){
		 
		this.getBlog();
		 
	},
	methods:{
		 
		getBlog:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_blog&a=my&ajax=1",
				dataType:"JSON",
		 
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.blogList=res.data.list;
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=fishing_blog&a=show&id="+id;
		},
		del:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认删除吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=fishing_blog&a=delete&ajax=1",
						dataType:"JSON",
						data:{
							id:item.id
						},	 
						success:function(res){
							skyJs.toast(res.message);
							if(res.error){
								
								return false;
							}
							var list=[];
							for(var i in that.blogList){
								if(that.blogList[i].id!=item.id){
									list.push(that.blogList[i])
								}
							}
							that.blogList=list;
						}
					})
				}
			})
			
		}  
	}
})