var App=new Vue({
	el:"#App",
	data:function(){
		return {
			localAddress:"去选择位置",
			pageData: {},
			recShop:[],
			keyword: "",
			pageLoad: false,
			shopList: {},
			isFirst:true,
			per_page:0,
			discount:"",
			price:"",
			choice:"",
			orderby:""
		}
	},
	created:function(){
		var localAddress=localStorage.getItem("wmo2o_localAddress");
		if(localAddress!="" && localAddress){
			this.localAddress=localAddress;
		}
		this.getPage();
		this.getShopList();
	},
	methods:{
		sortParent:function(ops){
			this.discount=ops.discount;
			this.price=ops.price;
			this.choice=ops.choice;
			this.orderby=ops.orderby;
			this.per_page=0;
			this.isFirst=true;
			console.log(ops)
			this.getList();
		},
		goAddr:function(){
			window.location="/module.php?m=wmo2o_addr"
			 
		},
		search: function() { 
			window.location="/module.php?m=wmo2o_search&keyword=" + encodeURI(this.keyword)
			 
		},
		goShop: function(shopid) {
			window.location="/module.php?m=wmo2o_shop&shopid=" + shopid
			 
		},
		goProduct: function(id) {
			window.location="/module.php?m=wmo2o_product&id=" + id
		},
		gourl: function(url) {
			window.location=url;
		},
		getPage: function() {
			var latlng=GPS.get();
			var that = this;
			$.ajax({
				url:  "/module.php?m=wmo2o&ajax=1",
				data:{
					lat:latlng.lat,
					lng:latlng.lng
				},
				dataType:"json",
				success: function(res) {	
					that.recShop=res.data.recShop;
					that.pageData = res.data;
					that.pageLoad = true;
					
				}
			})
		},
		
		getShopList: function() {
			var that = this;
			var latlng=GPS.get();
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			
			$.ajax({
				url: "/module.php?m=wmo2o_shoplist&a=list&ajax=1",
				data: {
					discount:that.discount,
					price:that.price,
					choice:that.choice,
					orderby:that.orderby,
					lat:latlng.lat,
					lng:latlng.lng
				},
				dataType: "json",
				success: function(res) {
					 
					
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.isFirst=false;
						that.shopList = res.data.shopList;
					}else{
						var list=that.shopList;
						for(var i in res.data.shopList){
							list.push(res.data.shopList[i]);
						}
						that.shopList=list;
					}
				}
			})
		}
	}
})