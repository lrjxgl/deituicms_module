var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			 
			tab:"guest"
			
		}
	},
	created:function(){
	 
		 
	},
	methods:{
		setTab:function(t){
			this.tab=t;
			 
		}
		 
	}
})
