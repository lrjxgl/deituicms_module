var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			pageLoad:false,
			data:{},
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
			window.location="/module.php?m=house_resource&a=show&id="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_agent&a=show&ajax=1",
				data:{
					id:id
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.list=res.data.list;
					that.data=res.data.data;
					that.user=res.data.user;
				}
			})
		}
 
		 
	}
})