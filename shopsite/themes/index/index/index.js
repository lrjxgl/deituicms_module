var App=new Vue({
	el:"#App",
	data:function(){
		return {
			globalData:globalData, 
			shop:{},
			catList:{},
			productid:0,
			 
			shareClass:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=shopsite&ajax=1",
				data:{
					shopid:this.globalData.shopid
				},
				dataType:"json",
				success:function(res){
					that.shop=res.data.shop;
					that.catList=res.data.shop_catlist
				}
			})
		},
		goDetail:function(){
			this.globalData.pageTab='detail';
		},
		goShow:function(e){
			this.productid=e.id;
			this.globalData.pageTab='show';
			
			 
		}
	}
	
})