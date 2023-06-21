
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			catList:[],
			list:[],
			catid:0,
			shopid:0,
			shop:{},
			shopFavClass:"",
			globalData:globalData, 
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		this.shopid=shopid;
		this.getPage();
		this.getProduct();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=mmjz_shop&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.shop=res.data.shop;
					if (res.data.isfav == 1) {
						that.shopFavClass = "btn-fav-active";
					} 
				}
			})
		},
		setCat:function(catid){
			this.catid=catid;
			this.isFirst=true;
			this.per_page=0
			this.getProduct()
		},
		getProduct:function(){
			var that=this;
			if(this.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=mmjz_product&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid,
					catid:this.catid,
					per_page:this.per_page
				},
				success:function(res){
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.catList=res.data.catList;
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
						that.isFirst=false;
					}
					
					
					
				}
			})
		},
		goProduct:function(id){
			this.globalData.pageTab="product";
			this.globalData.productid=id;
		},
		favShopToggle: function(shopid) {
			var that = this;
			$.ajax({
				url: "/index.php?m=fav&a=toggle&ajax=1",
				data: {
					tablename: "mod_mmjz_shop",
					objectid: this.shopid
				},
				dataType: "json",
				success: function(res) {
					if (res.error) {
						skyToast(res.message)
					}
		
					if (res.data == "add") {
						that.shopFavClass = 'btn-fav-active';
					} else {
						that.shopFavClass = '';
					}
				}
			})
		}
	}
})