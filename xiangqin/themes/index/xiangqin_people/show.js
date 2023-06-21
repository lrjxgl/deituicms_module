var App=new Vue({
	el:"#App",
	data:function(){
		return {
			data:{},
			zhModal:false,
			zhContent:"",
			userid:0,
			bbModal:false,
			bbContent:""
		}
	},
	created:function(){
		this.userid=userid;
	},
	methods:{
		showModal:function(){
			this.zhModal=true;
		},
		zhaohuSave:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=xiangqin_zhaohu&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:that.zhContent,
					touserid:that.userid
				},
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					}
					that.zhModal=false;
				}
			})
		},
		bbSave:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=xiangqin_biaobai&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:that.bbContent,
					touserid:that.userid
				},
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					}
					that.bbModal=false;
				}
			})
		}
	}
})