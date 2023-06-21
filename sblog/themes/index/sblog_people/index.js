var App = new Vue({
	el: "#App",
	data: function() {
		return {
			list: [],
			pageLoad: false,
			type: "recommend",
			per_page: 0,
			isFirst: true,
			keyword: ""
		}
	},
	created: function() {
		if(!this.getCache()){
			this.getPage();
		}
	},
	onReachBottom: function() {
		this.getList();
	},
	methods: {
		getCache:function(){
			var k="page-m-sblog_people";
			var res=localStorage.getItem(k);
			 
			if(res!=null){
				var d=JSON.parse(res);
				if(Date.parse(new  Date())/1000>d.expire){
					return false;
				}
				
				this.list=d.list;
				this.isFirst=d.isFirst;
				this.pageLoad=d.pageLoad;
				this.type=d.type;
				this.per_page=d.per_page;
				this.keyword=d.keyword;
				
				return true;
			}
			return false;
		},
		setCache:function(){
			var k="page-m-sblog_people";
			var v=this.$data;
			v.expire=Date.parse(new  Date())/1000+120;
			localStorage.setItem(k,JSON.stringify(v));
		},
		setType: function(type) {
			var that = this;
			this.type = type;
			that.per_page = 0;
			that.isFirst = true;
			this.getList();
		},
		search: function() {
			var that = this;
			that.per_page = 0;
			that.isFirst = true;
			console.log("search")
			that.getList();
		},
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=sblog_people&a=list&ajax=1",
				data: {
					type: that.type
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.per_page = res.data.per_page;
					that.list = res.data.list;
					that.setCache();
				}
			})
		},
		getList: function() {
			var that = this;
			if (this.per_page == 0 && !this.isFirst) {
				return false;
			}
			$.ajax({
				url: "/module.php?m=sblog_people&a=list&ajax=1",
				data: {
					type: that.type,
					per_page: that.per_page,
					keyword: that.keyword
				},
				dataType: "json",
				success: function(res) {
					that.pageLoad = true;
					that.per_page = res.data.per_page;
					if (that.isFirst) {
						that.list = [];
						that.list = res.data.list;
						that.isFirst = false;
					} else {
						for (var i in res.data.list) {
							that.list.push(res.data.list[i]);
						}
					}
					that.setCache();
				}
			})
		},
		goUser: function(userid) {
			window.location = "/module.php?m=sblog_home&userid=" + userid;
		},
		followToggle: function(item) {
			var that = this;
			$.ajax({
				url: "/index.php?m=follow&a=Toggle&ajax=1",
				dataType: "json",
				data: {
					t_userid: item.userid
				},
				success: function(res) {
					if (res.error) {
						uni.showToast({
							title: res.message
						});
						return false;
					}
					item.isfollow = res.follow;

				}
			});
		}
	}
});
