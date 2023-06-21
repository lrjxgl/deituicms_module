var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			tab:"post"
		}
	},
	created:function(){
		this.getPost();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
			switch(t){
				case "post":
					this.getPost();
					break;
				default:
					this.getAccept();
					break;
			}
		},
		getPost:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=yxq_paper&a=myapi&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
				}
			})
		},
		getAccept:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=yxq_paper&a=userapi&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
				}
			})
		}
	}
})