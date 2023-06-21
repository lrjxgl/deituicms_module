var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			news:{},
			list:[],
			content:"",
			scf:{}
		}
	},
	created: function() {
		this.getPage();
		this.getConfig();
	},
	methods: {
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=sjsj_guest&a=sjsj&ajax=1",
				data: {
					touserid: touserid,
					newsid: newsid
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.list = res.data.list;
					that.news=res.data.news;
					console.log(res.data);
				}
			})
		},
		submit: function() {
			var that=this;
			 
			$.post("/module.php?m=sjsj_guest&a=save&ajax=1", {
				content: this.content,
				newsid: newsid,
				touserid: touserid
			}, function(res) {
				that.getPage();
				that.content="";
			}, "json")
		},
		getConfig:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_config&ajax=1",
				 
				dataType:"json",
				success:function(res){
					that.scf=res.data.scf;
				}
			})
		},
		buy:function(item){
			skyJs.confirm({
				content:"确认花"+this.scf.sold_money+"元买断吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=sjsj_news&a=buy&ajax=1",
						dataType:"json",
						data:{
							newsid:item.newsid
						},
						success:function(res){
							skyToast(res.message)
						}
					})
				}
			})
		},
	}
})
$(function() {
	setInterval(function() {
		app.getPage();
	}, 10000)
	 
})
