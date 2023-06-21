var App=new  Vue({
	el:"#App",
	data: function() {
		return {
			pageLoad: false,
			list: [],
			per_page: 0,
			isFirst: true,
			id: 0,
			topic: {}
		}
	},
	created: function() {
		this.id = topicid;
		this.getPage();
		this.getList();
	},
	onReachBottom: function() {
		this.getList();
	},
	onPullDownRefresh: function() {
		this.getPage();
		uni.stopPullDownRefresh();
	},
	onShareAppMessage: function() {

	},
	onShareTimeline: function() {

	},
	methods: {
		 
		goBlog: function(id) {
			window.location="/module.php?m=sblog_blog&a=show&id="+id;
		},
		getPage: function() {
			var that = this;
			$.ajax({
				url:  "/module.php?m=sblog_topic&a=show&ajax=1",
				data: {
					id: this.id
				},
				dataType:"json",
				success: function(res) {
					that.topic = res.data.topic;
					
					that.pageLoad = true;
				}
			})
		},
		getList: function() {
			var that = this;
			if (that.per_page == 0 && !that.isFirst) {
				return false;
			}
			$.ajax({
				url:  "/module.php?m=sblog_blog&a=topic&ajax=1",
				data: {
					per_page: that.per_page,
					id: this.id
				},
				dataType:"json",
				success: function(res) {
					that.per_page = res.data.per_page;
					if (that.isFirst) {
						that.list = res.data.list;
						that.isFirst = false;
					} else {
						for (var i in res.data.list) {
							that.list.push(res.data.list[i]);
						}
					}

				}
			})
		}
	},
});
