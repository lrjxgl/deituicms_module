var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			pageLoad:false,
			type:"",
			isFirst:true,
			per_page:0,
			hbListShow:false,
			hbList:[],
			activeItem:{},
			hbid:0
		}
	},
	created:function(){
		 
		if(!this.getCache()){
		 
			this.getPage();
		}
	},
	watch:{
		list:function(o,n){
			this.setCache();
		},
		hbList:function(o,n){
			this.setCache();
		}
	},
	methods:{
		getCache:function(){
			var v=localStorage.getItem("car_shop_order");
			if(v){
				var res=JSON.parse(v);
				this.list=res.list;
				this.pageLoad=res.pageLoad;
				this.type=res.type;
				this.per_page=res.per_page;
				this.isFirst=res.isFirst;
				this.hbList=res.hbList;
				var time=Date.parse(new Date())/1000;
				if(res.expire<time){
					return false;
				}
				return true;
			}else{
				return false;
			}
			
		},
		setCache:function(){
			var v=this.$data;
			v.expire= Date.parse(new Date())/1000+60; 
			localStorage.setItem("car_shop_order",JSON.stringify(v));
		},
		setType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		showHb:function(item){
			this.hbListShow=true;
			this.activeItem=item;
		},
		sendHb:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=car_sold_product&a=hbpay&ajax=1",
				dataType:"json",
				data:{
					productid:this.activeItem.productid,
					hbid:that.hbid
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;						
					}
					that.updateItem(that.activeItem.productid) 
				}
			})
			
		},
		updateItem:function(productid){
			var that=this;
			$.ajax({
				url:"/module.php?m=car_sold_product&a=show&ajax=1",
				dataType:"json",
				data:{
					productid:productid
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;						
					}
					var list=[];
					for(var i in that.list){
						if(that.list[i].productid==productid){
							console.log(res.data.data)
							list.push(res.data.data);
						}else{
							list.push(that.list[i]);
						}
					}
					that.list=list;
				}
			})
		},
		goDetail:function(productid){
			window.location="/module.php?m=car_sold_product&a=show&productid="+productid;
		},
		copy:function(productid){
			window.location="/module.php?m=car_sold_product&a=copy&productid="+productid;
		},
		goEdit:function(productid){
			window.location="/module.php?m=car_sold_product&a=add&productid="+productid;
		},
		toggleStatus:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=car_sold_product&a=status&ajax=1&productid="+item.productid,
			
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					item.status=res.data;
					 
				}
			})
		}, 
		del:function(productid){
			var that=this;
			skyJs.confirm({
				title:"删除提示",
				content:"删除后不可恢复，确认删除吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=car_sold_product&a=delete&ajax=1&productid="+productid,
					
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
						url:"/module.php?m=car_sold_product&a=recommend&ajax=1&productid="+item.productid,
					
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
				url:"/module.php?m=car_sold_product&a=my&ajax=1",
				
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.pageLoad=true;
					that.list=res.data.list;
					
					that.per_page=res.data.per_page;
					that.hbList=res.data.hbList;
					console.log(res)
					that.isFirst=true;
					that.setCache();
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=car_sold_product&a=my&ajax=1",
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
					that.hbList=res.data.hbList; 
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		}
	}
})