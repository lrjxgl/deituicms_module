var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=household_team&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		}
	}
})