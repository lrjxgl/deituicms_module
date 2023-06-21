var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			pageLoad:false,
			type:"online",
			isFirst:true,
			per_page:0
		}
	},
	created:function(){
		 
		if(!pageCache.getCache(this,"freeshop_shop_order")){
		 
			this.getPage();
		}
	},
	watch:{
		list:function(n,o){
			pageCache.setCache(this,"freeshop_shop_order")
		}
	},
	methods:{
		 
		setType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			pageCache.setCache(this,"freeshop_shop_order")
			this.getList();
		},
		goDetail:function(productid){
			window.location="/module.php?m=freeshop_product&a=show&productid="+productid;
		},
		copy:function(productid){
			window.location="/module.php?m=freeshop_product&a=copy&productid="+productid;
		},
		 
		del:function(productid){
			var that=this;
			skyJs.confirm({
				title:"删除提示",
				content:"删除后不可恢复，确认删除吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=freeshop_product&a=delete&ajax=1&productid="+productid,
					
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
			})
			 
			
		},
		recommend:function(item){
			var that=this;
			 
			skyJs.confirm({
				content:"上热门需要"+recommend_money+"元",
				success:function(){
				 
					$.ajax({
						url:"/module.php?m=freeshop_product&a=recommend&ajax=1&productid="+item.productid,
					
						dataType:"json",
						success:function(res){
							if(res.error){
								skyToast(res.message);
								return false;
							}
							item.isrecommend=1;
							 
						}
					})
				}
			})
			 
			
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_product&a=my&ajax=1",
				
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.pageLoad=true;
					that.list=res.data.list;
					that.isFirst=true;
					that.per_page=res.data.per_page;
					pageCache.setCache(that,"freeshop_shop_order") 
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=freeshop_product&a=my&ajax=1",
				data:{
					per_page:that.per_page,
					type:that.type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					 
					that.isFirst=false;
					that.per_page=res.data.per_page;
					pageCache.setCache(that,"freeshop_shop_order")
				}
			})
		}
	}
})