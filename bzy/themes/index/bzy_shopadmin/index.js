var App=new Vue({
	el:"#app",
	data:function(){
		return {
			event:{},
			list:[],
			tab:"order",
			eventid:0,
			stat:{}
		}
	},
	created:function(){
		this.eventid=eventid;
		this.getStat();
	},
	methods:{
		setTab:function(tab){
			this.tab=tab;
		},
		getPage:function(){
			
		},
		getStat:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=bzy_shopadmin&a=stat&ajax=1",
				dataType:"json",
				data:{
					eventid:this.eventid
				},
				success:function(res){
					that.stat=res.data;
				}
			})
		}
		 
	}
})