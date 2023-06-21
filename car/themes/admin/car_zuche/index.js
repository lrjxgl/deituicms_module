var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			pageLoad:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
 
		goDetail:function(productid){
			window.location="/module.php?m=car_zuche&a=show&productid="+productid;
		},
		edit:function(productid){
			window.location="/moduleadmin.php?m=car_zuche&a=add&productid="+productid;
		},
		del:function(productid){
			var that=this;
			if(confirm("删除后不可恢复，确认删除吗？")){
				$.ajax({
					url:"/moduleadmin.php?m=car_zuche&a=delete&ajax=1&productid="+productid,
				
					dataType:"json",
					success:function(res){
						var list=that.list;
						var newlist=[];
						for(var i=0;i<list.length;i++){
							if(list[i].productid!=productid){
								newlist.push(list[i]);
							}
						}
						that.list=newlist;
						 
					}
				})
			}
			
		},
		pass:function(productid){
			var that=this;
			if(confirm("确认通过审核吗？")){
				$.ajax({
					url:"/moduleadmin.php?m=car_zuche&a=pass&ajax=1&productid="+productid,
				
					dataType:"json",
					success:function(res){
						var list=that.list;
						var newlist=[];
						for(var i=0;i<list.length;i++){
							if(list[i].productid!=productid){
								newlist.push(list[i]);
							}
						}
						that.list=newlist;
						 
					}
				})
			}
			
		},
		forbid:function(productid){
			var that=this;
			if(confirm("确认审核不通过吗？")){
				$.ajax({
					url:"/moduleadmin.php?m=car_zuche&a=pass&ajax=1&productid="+productid,
				
					dataType:"json",
					success:function(res){
						var list=that.list;
						var newlist=[];
						for(var i=0;i<list.length;i++){
							if(list[i].productid!=productid){
								newlist.push(list[i]);
							}
						}
						that.list=newlist;
						 
					}
				})
			}
			
		},
		toggleRecommend:function(item){
			$.ajax({
				url:"/moduleadmin.php?m=car_zuche&a=recommend&ajax=1&productid="+item.productid,
			
				dataType:"json",
				success:function(res){
					item.isrecommend=res.data;
					 
				}
			})
		},
		toggleStatus:function(item){
			$.ajax({
				url:"/moduleadmin.php?m=car_zuche&a=status&ajax=1&productid="+item.productid,
			
				dataType:"json",
				success:function(res){
					item.status=res.data;
					 
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=car_zuche&ajax=1&type="+type,
				
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					that.pageLoad=true;
					that.list=res.data.list;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/moduleadmin.php?m=car_zuche&ajax=1",
				data:{
					per_page:that.per_page,
					type:type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					 
					if(this.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					
				}
			})
		}
	}
})