var App=new Vue({
	el:"#App",
	data:function(){
		return {
			prompt:"",
			num:1
		}
	},
	methods:{
		submit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_book&a=writesave&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					prompt:this.prompt,
					num:this.num
				},
				success:function(res){
					skyToast(res.message)
					that.prompt="";
					that.num=1;
				}
			})
		}
	}
})