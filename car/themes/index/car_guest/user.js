var app = new Vue({
				el: "#app",
				data: function() {
					return {
						pageLoad: false,
						pageData: [],
						list:[],
						isFirst: true,
						per_page: 0,
						hasNew: false,
						user: {},
						shop: {},
						content:"",
						
					}
				},
				created: function() {
					var that=this;
					this.getPage();
					setInterval(function() {
						that.checkNew();
					}, 20000)
				},
				methods: {
					checkNew: function() {
						var that = this;
						$.ajax({
							url: "/module.php?m=car_guest&a=checkNew&ajax=1",
							data: {
								shopid: shopid,
								id: id
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
							url: "/module.php?m=car_guest&a=user&ajax=1",
							data: {
								shopid: shopid,
								id: id
							},
							dataType: "json",
							success: function(res) {
								that.pageLoad = true;
								console.log(res.data.list)
								that.list = res.data.list
								that.per_page = res.data.per_page;
								that.user = res.data.user;
								that.shop = res.data.shop;
								that.hasNew = 0;
							}
						})
					},
					getList: function() {
						var that = this;
						if (!that.isFirst && that.per_page == 0) return false;
						$.ajax({
							dataType: "json",
							url: "/module.php?m=car_guest&a=user&ajax=1",
							data: {
								per_page: that.per_page,
								shopid: shopid
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
						var that=this;
						$.ajax({
							url:"/module.php?m=car_guest&a=save&ajax=1",
							dataType:"json",
							type:"POST",
							data:{
								content: this.content,
								id: id,
								shopid: shopid
							},
							success:function(res){
								that.getPage();
								that.content="";
							}
						})
						 
					}
				}
			})