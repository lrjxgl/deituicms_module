var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			list: [],
			isFirst: true,
			per_page: 0,
			hasNew: false,
			user: {},
			shop: []
		}
	},
	created: function() {
		this.getPage();
	},
	methods: {
		checkNew: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=car_guest&a=shop&a=checkNew&ajax=1",
				data: {
					shopid: shopid,
					userid: userid,
					author: "shop"
				},
				dataType: "json",
				success: function(res) {
					if (res.num == 1) {
						that.hasNew = 1;
					} else {
						that.hasNew = 0;
					}
				}
			})
		},
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=car_guest&a=shop&ajax=1",
				data: {
					shopid: shopid,
					userid: userid
				},
				dataType: "json",
				success: function(res) {

					that.list = res.data.list;
					that.per_page = res.data.per_page;
					that.hasNew = 0;
					that.user = res.data.user;
					that.shop = res.data.shop;
					that.pageLoad = true;
				}
			})
		},
		getList: function() {
			var that = this;
			if (!that.isFirst && that.per_page == 0) return false;
			$.ajax({
				dataType: "json",
				url: "/module.php?m=car_guest&a=shop&ajax=1",
				data: {
					per_page: that.per_page,
					shopid: shopid,
					userid: userid,
				},
				success: function(res) {
					if (that.isFirst) {
						that.list = res.data.list;
						that.isFirst = false;
					} else {
						for (var i in res.data.list) {
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page = res.data.per_page;
				}
			})
		},
		submit: function() {

		}
	}
})
$(function() {
	setInterval(function() {
		app.checkNew();
	}, 10000);
	$(document).on("click", "#submit", function() {
		var content = $("#content").val();
		$.post("/module.php?m=car_guest&a=shop&a=save&ajax=1", {
			content: content,
			id: id,
			shopid: shopid,
			userid: userid
		}, function(res) {
			app.getPage();
			$("#content").val("");
		}, "json")
	})
})
