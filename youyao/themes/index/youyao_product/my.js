var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		goAdd:function(productid){
			window.location="/module.php?m=youyao_product&a=add&productid="+productid
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=youyao_product&a=my&ajax=1",
				data:{
					catid:this.catid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=youyao_product&a=my&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		del:function(item){
			var that=this;
			skyJs.confirm({
				content:"删除后不可恢复，确认删除吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=youyao_product&a=delete&ajax=1&productid="+item.productid,
						dataType:"json",
						success:function(res){
							if(!res.error){
								var list=[];
								for(var i in that.list){
									if(that.list[i].productid!=item.productid){
										list.push(that.list[i])
									}
								}
								that.list=list; 
							}
						}
					})
				}
			})
		},
		toggleStatus:function(item){
			var that=this;
			var str="";
			if(item.status==1){
				str="确认下架吗"
			}else{
				str="确认上架吗"
			}
			skyJs.confirm({
				content:str,
				success:function(){
					$.ajax({
						url:"/module.php?m=youyao_product&a=status&ajax=1&productid="+item.productid,
						dataType:"json",
						success:function(res){
							if(!res.error){
								item.status=res.data; 
							}
						}
					})
				}
			})
		},
		toggleNum:function(item){
			var that=this;
			var str="";
			if(item.total_num==0){
				str="确认有库存了吗"
			}else{
				str="确认无货了吗"
			}
			skyJs.confirm({
				content:str,
				success:function(){
					$.ajax({
						url:"/module.php?m=youyao_product&a=num&ajax=1&productid="+item.productid,
						dataType:"json",
						success:function(res){
							if(!res.error){
								item.total_num=res.data; 
							}
						}
					})
				}
			})
		}
	}
})