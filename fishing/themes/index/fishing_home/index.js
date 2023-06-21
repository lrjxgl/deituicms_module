var App=new Vue({
	el:"#App",
	data:function(){
		return {
			blogList:[],
			pageLoad:false,
			user:{},
			blogFirst:true,
			blogPage:0,
			tab:"blog"
			
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		}, 
		goBlog:function(id){
			window.location="/module.php?m=fishing_blog&a=show&id="+id;
		},
		goPm:function(userid){
			window.location="/index.php?m=pm&a=detail&userid="+userid;
		},
		getPage:function(){
			var that=this;
			if(that.blogPage==0 && !that.blogFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_home&a=api&ajax=1",
				data:{
					userid:userid,
					per_page:that.blogPage
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					if(that.blogFirst){
						that.blogFirst=false;
						that.blogList=res.data.list;
						that.user=res.data.user;
					}else{
						for(var i in res.data.list){
							that.blogList.push(res.data.list[i])
						}
					}
					that.blogPage=res.data.per_page;
					
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