var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=cfd_reward&ajax=1",
				data:{
					cfdid:cfdid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.data;
				}
			})
		},
		addSubmit:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=cfd_reward&a=save&ajax=1",
				data:$("#addForm").serialize(),
				dataType:"json",
				type:"POST",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		},
		editSubmit:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=cfd_reward&a=save&ajax=1",
				data:$("#e"+item.id).serialize(),
				dataType:"json",
				type:"POST",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		},
		delSubmit:function(item){
			if(confirm("确认删除吗")){
				var that=this;
				$.ajax({
					url:"/moduleadmin.php?m=cfd_reward&ajax=1&a=delete",
					data:{
						id:item.id
					},
					dataType:"json",
					success:function(res){
						that.getPage();
					}
				})
			}
			
		},
		toggleStatus:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=cfd_reward&a=status&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					that.getPage();
				}
			})
		}
	}
})