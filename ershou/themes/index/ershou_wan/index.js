var app=new Vue({
	el:"#App",
	data:function(){
		return {
			 
			page:"index"
		}
	},
	created:function(){
		pageCache.getCache(this,'ershou_wan_index');
 
	},
	methods:{
		setPage:function(p){
			this.page=p;
			pageCache.setCache(this,'ershou_wan_index');
		}
	}
	 
})