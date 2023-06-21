var touch = new vueTouch();
touch.inMove = true;
var App = new Vue({
	el: "#App",
	data: function() {
		return {
			page: "product",
			pageLoad: false,
			list: {},
			shopid: shopid,
			shop: {},
			shopFavClass: "",
			tabClass: "",
			tabRowClass: "",
			sideClass: "",

		}
	},
	created: function() {
		this.getPage();

	},
	methods: {

		backTop: function() {
			$(window).scrollTop(0);
		},
		gourl: function(url) {
			uni.navigateTo({
				url: url
			})
		},

		headerMoreShow: function() {
			this.headerMoreClass = 'flex-col';
		},
		headerMoreHide: function() {
			this.headerMoreClass = '';
		},

		goGuest: function() {
			window.location = "/module.php?m=b2b_guest&a=user&shopid=" + shopid;

		},
		goProduct: function(id) {
			window.location = "/module.php?m=b2b_product&a=show&id=" + id + "&shopid=" + shopid

		},

		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=b2b_shop&ajax=1&shopid=" + shopid,
				dataType: "json",
				success: function(res) {


					that.shop = res.data.shop;
					if (res.data.isfav == 1) {
						that.shopFavClass = "btn-fav-active";
					}
					that.pageLoad = true;
					that.$nextTick(function(){
						initDot()
					})
				}
			});
		},
		favShopToggle: function(shopid) {
			var that = this;
			$.ajax({
				url: "/index.php?m=fav&a=toggle&ajax=1",
				data: {
					tablename: "mod_b2b_shop",
					objectid: shopid
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

$(window).on("scroll", function(e) {
	initDot();
})

function initDot(){
	var a = $(window).scrollTop();
	var b = $("#esDot").offset().top;
	if (a > b) {
		App.$data.sideClass = "sideClass"
		App.$data.tabClass = "tabClass";
		App.$data.tabRowClass = "tabRowClass";
	} else {
		App.$data.sideClass = ""
		App.$data.tabClass = "";
		App.$data.tabRowClass = "";
	}
}