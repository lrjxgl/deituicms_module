var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			 
			tab:"b2b_guest"
			
		}
	},
	created:function(){
	 
		var tab=localStorage.getItem("b2b_msg_tab");
		if(tab!=undefined){
			this.tab=tab;
		} 
	},
	methods:{
		setTab:function(t){
			this.tab=t;
			localStorage.setItem("b2b_msg_tab",t) 
		}
		 
	}
})
