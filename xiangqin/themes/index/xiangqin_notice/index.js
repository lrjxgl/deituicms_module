var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			 
			tab:"pm"
			
		}
	},
	created:function(){
	 
		var s=localStorage.getItem("xiangqin-notice");
		
		if(s!=null){
			this.tab=s;
		} 
	},
	methods:{
		setTab:function(t){
			this.tab=t;
			localStorage.setItem("xiangqin-notice",t); 
		}
		 
	}
})
