var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			 
			tab:"pm"
			
		}
	},
	created:function(){
		this.getCache()
		 
	},
	methods:{
		setCache:function(){
			sessionStorage.setItem("fishing-notice",JSON.stringify(this.$data));
		},
		getCache:function(){
			var v=sessionStorage.getItem("fishing-notice");
			if(v==undefined){
				return false;
			}else{
				var p=JSON.parse(v);
				this.tab=p.tab; 
		
				return true;
			}
		},
		setTab:function(t){
			this.tab=t;
			this.setCache(); 
		}
		 
	}
})
