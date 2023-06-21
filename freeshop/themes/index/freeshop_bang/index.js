var App=new Vue({
	el:"#App",
	data:function(){
		return {
			shopList:[],
			proList:[],
			tab:"shop"
		}
	},
	created:function(){
		this.getShop();
		this.getProduct();
	},
	methods:{
		getShop:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_bang&a=shop&ajax=1",
				dataType:"json",
				success:function(res){
					that.shopList=res.data.list;
				}
			})
		},
		getProduct:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_bang&a=product&ajax=1",
				dataType:"json",
				success:function(res){
					that.proList=res.data.list;
				}
			})
		},
		goDetail:function(productid){
			window.location="/module.php?m=freeshop_product&a=show&productid="+productid
		},
		goShop:function(shopid){
			window.location="/module.php?m=freeshop_home&shopid="+shopid
		}
	}
})