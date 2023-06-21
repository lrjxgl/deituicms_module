var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			bianhao:""
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
				url:"/module.php?m=vote_join&a=list&vid="+vid,
				data:{
					bianhao:that.bianhao,
					type:"hot"
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			});
		}
	}
});