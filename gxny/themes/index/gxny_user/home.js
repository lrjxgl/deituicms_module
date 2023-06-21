var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			pageLoad:false,
			user:{},
			proList:[],
			productid:0,
			isFirst:true,
			per_page:0
			 
		}
	},
	created:function(){
		this.getPage();
		this.getBlog()
	},
	methods:{
		 
		goBlog:function(id){
			window.location="/module.php?m=gxny_blog&a=show&id="+id;
		},
		goPm:function(userid){
			window.location="/index.php?m=pm&a=detail&userid="+userid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_user&a=api&ajax=1",
				data:{
					userid:userid
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.proList=res.data.proList;
					that.user=res.data.user;
				}
			})
		},
		getBlog:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_user&a=blog&ajax=1",
				data:{
					userid:userid,
					productid:this.productid,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					 
				}
			})
		},
		setProduct:function(productid){
			this.productid=productid;
			this.per_page=0;
			this.isFirst=true;
			this.getBlog();
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