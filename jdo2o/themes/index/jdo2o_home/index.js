var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			pageLoad:false,
			user:{}
			 
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		goPm:function(userid){
			window.location="/index.php?m=pm&a=detail&userid="+userid;
		},
		goBlog:function(id){
			window.location="/module.php?m=jdo2o_blog&a=show&id="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=jdo2o_home&a=api&ajax=1",
				data:{
					userid:userid
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.list=res.data.list;
					that.user=res.data.user;
				}
			})
		},
 
		followToggle:function(item){
			var that=this;
			$.ajax({
				url: "/index.php?m=follow&a=Toggle&ajax=1",
				dataType: "json",
				data: {
					t_userid: item.userid
				},
				success: function(res) {
					skyToast(res.message);
					if(res.error){
						return false;
					}
					item.isfollow = res.follow;
					
				}
			});
		}
	}
})