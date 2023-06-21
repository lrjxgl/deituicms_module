var App = new Vue({
	el: "#App",

	data: function() {
		return {
			tab: "list1"
		}
	},
	created: function() {
		this.getCache();
	},
	watch:{
		tab:function(n,o){
			this.setCache();
		}
	},
	methods: {
		getCache: function() {
			var v = localStorage.getItem("jdo2oIndex");
			if (v) {
				var res = JSON.parse(v);
				this.tab = res.tab;
				 
				var time = Date.parse(new Date()) / 1000;
				if (res.expire < time) {
					return false;
				}
				return true;
			} else {
				return false;
			}

		},
		setCache: function() {
			var v = this.$data;
			v.expire = Date.parse(new Date()) / 1000 + 300;
			localStorage.setItem("jdo2oIndex", JSON.stringify(v));
		}
		 
	}
})
