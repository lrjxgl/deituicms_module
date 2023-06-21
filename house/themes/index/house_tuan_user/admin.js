var App=new Vue({
	el:"#App",
	data:function(){
		return {
				userList:[]
		}
	
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_user&a=admin&ajax=1&tid="+tid,
				dataType:"json",
				success:function(res){
					that.userList=res.data.list;
				}
			})
		},
		qiandao:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_user&a=checkin&ajax=1&id="+item.id,
				dataType:"json",
				success:function(res){
					that.getList();
				}
			})
		},
		send:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_user&a=sendhongbao&ajax=1&id="+item.id,
				dataType:"json",
				success:function(res){
					that.getList();
				}
			})
		}
	}	
})