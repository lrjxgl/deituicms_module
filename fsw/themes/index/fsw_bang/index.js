var App=new Vue({
	el:"#App",
	data:function(){
		return {
			ppList:[]
		}
	},
	created:function(){
		this.people();
	},
	methods:{
		people:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_bang&a=people&ajax=1",
				dataType:"json",
				success:function(res){
					that.ppList=res.data.list;
				}
			})
		}
	}
})