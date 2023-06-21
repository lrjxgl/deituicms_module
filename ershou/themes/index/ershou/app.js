var app=new Vue({
	el:"#App",
	data:function(){
		return {
			page:"index",
			search_word:""
		}
	},
	created:function(){
		pageCache.getCache(this,'ershou_index');
	},
	methods:{
		setPage:function(p){
			this.page=p;
			pageCache.setCache(this,'ershou_index');
		},
		goSearch:function(){
			window.location="/module.php?m=ershou_search&keyword="+this.search_word
		}
	}
})