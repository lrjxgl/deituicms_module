var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			keyword:"",
			page:"product",
			list:[],
			isFirst:true,
			per_page:0
		}
	},
	created:function(){
		this.keyword=keyword;
		this.getPage();
		var that=this;
		 
		$(window).scroll(function(){
			
			if(that.per_page==0 || that.inScroll){
				return false;
			}
			
			if($("#loadMore").offset().top-50<$(window).scrollTop()+$(window).height()){
				that.inScroll=true;
				that.getPage();
				setTimeout(function(){
					that.inScroll=false;
				},1000)
			}
			 
		})
	},
	methods:{
		goProduct:function(shopid,id){
			window.location="/module.php?m=wmo2o_product&a=show&shopid="+shopid+"&id="+id;
		},
		goShop:function(shopid){
			window.location="/module.php?m=wmo2o_shop&shopid="+shopid;
		},
		search:function(){
			this.getPage();
		},
		setPage:function(page){
			this.page=page;
			this.pageLoad=false;
			this.list=[];
			this.isFirst=true;
			this.per_page=0;
			this.getPage();
		},
		getPage:function(){
			if(this.page=="product"){
				this.getProduct();
			}else{
				this.getShop();
			}
		},
		getShop:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=wmo2o_search&a=shop&ajax=1",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
				}
			})
		},
		getProduct:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=wmo2o_search&a=product&ajax=1",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
				}
			})
		}
	}
});