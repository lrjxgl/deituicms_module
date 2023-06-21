var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			 
			tab:"shop_guest"
			
		}
	},
	created:function(){
	 
		var tab=localStorage.getItem("wmo2o_msg_tab");
		if(tab!=undefined){
			this.tab=tab;
		} 
	},
	methods:{
		setTab:function(t){
			this.tab=t;
			localStorage.setItem("wmo2o_msg_tab",t) 
		}
		 
	}
})
