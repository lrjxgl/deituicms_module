var App=new Vue({
	el:"#App",
	data: function() {
		return {
			shop: {},
			data: {},
			pageLoad:false,
			shareClass:""
		}
	},
	created: function() {
		this.getPage()
	},
	methods: {
		goShop:function(){
			window.location="/module.php?m=shopsite&shopid="+shopid;
		},
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=shopsite_product&a=show&ajax=1&id=" + id,
				data:{
					shopid:shopid
				},
				dataType: "json",
				success: function(res) {
					that.data = res.data.data;
					that.pageLoad=true;
					
				}
			})
		}

	}
})
