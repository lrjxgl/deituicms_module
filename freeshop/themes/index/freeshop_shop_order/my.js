 
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			pageLoad:false,
			tab:"",
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		if( !this.getCache()){
			this.tab=type;
			this.getPage();
		}
	},
	methods:{
		getCache:function(){
			var v=localStorage.getItem("freeshop_shop_order");
			if(v){
				var res=JSON.parse(v);
				this.list=res.list;
				this.pageLoad=res.pageLoad;
				this.tab=res.tab;
				this.per_page=res.per_page;
				this.isFirst=res.isFirst;
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
			v.expire= Date.parse(new Date())/1000+300; 
			localStorage.setItem("freeshop_shop_order",JSON.stringify(v));
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_shop_order&a=my&ajax=1",
				data:{
					type:this.tab
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.setCache();
				}
			})
		},
		setType:function(t){
			this.tab=t;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				skyToast("加载完毕");
				return false;
			}
			$.ajax({
				url:"/module.php?m=freeshop_shop_order&a=my&ajax=1",
				data:{
					per_page:that.per_page,
					type:this.tab
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					 
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					 
					that.setCache();
				}
			})
		},
		goProduct:function(productid){
			window.location="/module.php?m=freeshop&a=show&productid="+productid;
		},
		goDetail:function(orderid){
			window.location="/module.php?m=freeshop_shop_order&a=show&orderid="+orderid;
		},
		loadMore:function(){
			this.getList();
		} 
	}
})