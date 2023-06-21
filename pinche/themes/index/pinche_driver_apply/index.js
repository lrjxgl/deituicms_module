var App=new Vue({
	el:"#App",
	data:function(){
		return {
			page:"a",
			data:{},
			canApply:false,
			apply:{}
		}
	},
	created:function(){
		
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_driver_apply&ajax=1",
				dataType:"json",
				success:function(res){
					that.canApply=res.data.canApply;
					that.apply=res.data.apply;
					that.data=res.data.data;
				}
			})
		},
		submitApply:function(){
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				dataType:"json",
				type:"POST",
				data:$("#form").serialize(),
				url:"/module.php?m=pinche_driver_apply&a=save&ajax=1",
				success:function(res){
					skyToast(res.message)
				}
			})
		}
	}
})