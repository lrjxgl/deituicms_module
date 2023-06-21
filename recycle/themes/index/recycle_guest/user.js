wsApp.getWsKey();
var wsclient_to="recycleShop"+shopid;

var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			pageData: [],
			isFirst: true,
			per_page: 0,
			hasNew: false,
			content: ""
		}
	},
	created: function() {
		this.getPage();
		var that=this;
		if(wsApp.on){
			wsApp.wsMessage=function(e){
			 
				if(e.page!=undefined && e.page=="recycle/recycle_guest"){
					if(e.wsclient_to!=wsApp.wsKey){
						return false;
					}
					if(e.type=="say"){
						that.getPage();
					}
				}
			}
		}else{
			setInterval(function() {
				app.checkNew();
			}, 10000);
		}
	},
	methods: {
		checkNew: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=recycle_guest&a=recycle&a=checkNew&ajax=1",
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
				url: "/module.php?m=recycle_guest&a=recycle&ajax=1",
				data: {
					shopid: shopid,
					id: id
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.pageData = res.data
					that.per_page = res.data.per_page;
					that.hasNew = 0;
					that.$nextTick(function(){
						window.scrollTo(0,10000); 
					})
				}
			})
		},
		getList: function() {
			var that = this;
			if (!that.isFirst && that.per_page == 0) return false;
			$.ajax({
				dataType: "json",
				url: "/module.php?m=recycle_guest&a=recycle&ajax=1",
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
			var that = this;
			if (this.content == '') {
				skyJs.toast("请输入内容");
			}
			if (!postCheck.canPost()) {
				return false;
			}
			console.log("submit")
			$.post("/module.php?m=recycle_guest&a=save&ajax=1", {
				content: that.content,
				id: id,
				shopid: shopid
			}, function(res) {
				
				
				 
				if(wsApp.on){
					var msg=JSON.stringify({
						
						wsclient_to: wsclient_to,
						page:"recycle/recycle_guest",
						type: "say",
						content: that.content
					})
					that.content = "";
					wsApp.ws.send(msg);
				}else{
					that.content = "";
				}
				
				app.getPage();
			}, "json")
		}
	}
})
