var App=new Vue({
	el:"#App",
	data:function(){
		return {
			data:{},
			ppid:0,
			 
		}
	},
	created:function(){
		this.ppid=ppid;
		this.getPage();
	},
	
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp&a=show&ajax=1",
				data:{
					ppid:this.ppid
				},
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
				}
			})
		},
		ppFollow:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp&a=followToggle&ajax=1",
				data:{
					ppid:this.ppid
				},
				dataType:"json",
				success:function(res){
					that.data.isFollow=res.data.isFollow;
				}
			})
		}
	}
})