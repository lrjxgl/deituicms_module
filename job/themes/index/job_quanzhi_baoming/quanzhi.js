var App=new Vue({
	el:"#App",
	data:function(){
		return {
			jianli:{},
			showJianli:false,
		}
	},
	created:function(){
		
	},
	methods:{
		getJianli:function(userid){
			var that=this;
			$.ajax({
				url:"/module.php?m=job_jianli&a=get&ajax=1",
				dataType:"json",
				data:{
					userid:userid
				},
				success:function(res){
					that.jianli=res.data.data;
					that.showJianli=true;
					console.log(that.jianli)
				}
				
			})
		}
	}
})