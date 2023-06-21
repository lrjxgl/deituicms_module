var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			pageLoad:false
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=mdish_view&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					 
					that.list=res.data.list;
					console.log(that.list)
					that.pageLoad=true;
				}
			})
		}
	}
})