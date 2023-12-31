var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			keyword:"",
			page:"product"
		}
	},
	created:function(){
		this.keyword=keyword;
		this.getPage();
	},
	methods:{
		goProduct:function(shopid,id){
			window.location="/module.php?m=csc_product&a=show&shopid="+shopid+"&id="+id;
		},
		goShop:function(shopid){
			window.location="/module.php?m=csc_shop&shopid="+shopid;
		},
		search:function(){
			this.getPage();
		},
		setPage:function(page){
			this.page=page;
			this.pageLoad=false;
			this.pageData={};
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
			$.ajax({
				url:"/module.php?m=csc_search&a=shop&ajax=1",
				data:{
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		},
		getProduct:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_search&a=product&ajax=1",
				data:{
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		}
	}
});