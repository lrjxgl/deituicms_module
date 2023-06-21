var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			list: [],
			isFirst:true,
			per_page:0
		}
	},
	created: function() {
		this.getPage();
	},
	methods: {
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=fenlei_guest&a=fenlei&ajax=1",
				data: {
					touserid: touserid,
					id: id
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.list = res.data.list
					that.per_page=res.data.per_page;
				}
			})
		},
		getPage: function() {
			var that = this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url: "/module.php?m=fenlei_guest&a=fenlei&ajax=1",
				data: {
					touserid: touserid,
					id: id,
					per_page:this.per_page
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					if(that.isFirst){
						that.list = res.data.list
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
						that.isFirst=false;
					}
					that.per_page=res.data.per_page;
		 
				}
			})
		},
		submit: function() {

		}
	}
})
$(function() {
	setInterval(function() {
		app.getPage();
	}, 20000)
	$(document).on("click", "#submit", function() {
		var content = $("#content").val();
		$.post("/module.php?m=fenlei_guest&a=save&ajax=1", {
			content: content,
			id: id,
			touserid: touserid
		}, function(res) {
			app.getPage();
			$("#content").val("");
		}, "json")
	})
})
