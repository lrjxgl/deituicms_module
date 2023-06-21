var App=new Vue({
	el:"#App",
	data:function(){
		return {
			prompt:"讲童话故事",
			mapp:"jianggushi"
		}
	},
	methods:{
		send:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_mapp&a=createsave&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					prompt:this.prompt,
					mapp:this.mapp
				},
				success:function(res){
					
				}
			})
		}
	}
})