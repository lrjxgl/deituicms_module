wsApp.wsKey="recycleShop"+shopid;
var wsclient_to;
wsApp.getUserClient(userid);
wsApp.wsInit();
var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			pageData: [],
			isFirst: true,
			per_page: 0,
			hasNew: false,
			content:""
		}
	},
	created: function() {
		var that=this;
		this.getPage();
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
				url: "/moduleshop.php?m=recycle_guest&a=recycle&a=checkNew&ajax=1",
				data: {
					shopid: shopid,
					userid: userid
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
				url: "/moduleshop.php?m=recycle_guest&a=recycle&ajax=1",
				data: {
					shopid: shopid,
					userid: userid
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.pageData = res.data;
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
				url: "/moduleshop.php?m=recycle_guest&ajax=1",
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
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/moduleshop.php?m=recycle_guest&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content: this.content,
					id: id,
					shopid: shopid,
					userid: userid
				},
				success:function(res){
					
					if(wsApp.on){ 
						var msg=JSON.stringify({
							wsclient_to: wsclient_to,
							type: "say",
							page:"recycle/recycle_guest",
							content: that.content
						})
						wsApp.ws.send(msg);
					}
					that.content="";
					app.getPage();
				}
			}) 
			
		}
	}
})
 