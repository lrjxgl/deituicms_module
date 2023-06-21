var App=new Vue({
	el:"#app",
	data:function(){
		return {
			joinList:{},
			pageLoad:false,
			 
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		search:function(){
			this.getPage();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=shanxin_join&a=list&ajax=1&sid="+sid,
				dataType:"json",
				success:function(res){
					that.joinList=res.data.list;
					that.pageLoad=true;
				}
			});
		}
	}
});