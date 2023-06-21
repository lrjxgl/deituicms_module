var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			user:{},
			shop:{},
			userid:0,
			isFollow:0
		}
	},
	created:function(){
		this.userid=userid;
		this.getPage();
		this.getList();
		this.getFollow();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_home&a=user&ajax=1",
				data:{
					userid:this.userid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.user=res.data.user;
					that.shop=res.data.shop; 
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=ershou_home&a=product&ajax=1",
				data:{
					userid:this.userid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getFollow:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=isFollow&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.userid
				},
				success:function(res){
					that.isFollow=res.data;
					 
				}
			})
		},
		toggleFollow:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=toggle&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.userid
				},
				success:function(res){
					if(!res.error){
						that.isFollow=res.follow; 
					}else{
						skyToast(res.message)
					}
					
					 
				}
			})
		},
	}
})