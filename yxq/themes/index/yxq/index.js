var App=new Vue({
	el:"#vApp",
	data:function(){
		return {
			paper:{},
			showPaper:false
		}
	},
	created:function(){
		 
	},
	methods:{
		getPaper:function(gender,type){
			if(!postCheck.canPost()){
				return false;
			}
			var that=this;
			$.ajax({
				url:"/module.php?m=yxq_paper&a=get&ajax=1",
				dataType:"json",
				data:{
					gender:gender,
					type:type
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.showPaper=true;
					that.paper=res.data.paper;
				}
			})
		}
	}
})