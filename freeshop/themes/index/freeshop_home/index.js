var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			pageLoad:false,
			shop:{},
			isFollow:0,
			per_page:0,
			isFirst:false 
		}
	},
	created:function(){
		this.isFollow=isFollow;
		this.getPage();
	},
	methods:{
		 
		goDetail:function(productid){
			window.location="/module.php?m=freeshop_product&a=show&productid="+productid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_home&a=api&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.list=res.data.list;
					that.shop=res.data.shop;
					
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=freeshop_home&a=api&ajax=1",
				data:{
					shopid:shopid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					 
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
				}
			})
		},
		toggleFollow:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_home&a=togglefollow&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.isFollow=res.data.isFollow;
				}
			})
		}
  
	}
})