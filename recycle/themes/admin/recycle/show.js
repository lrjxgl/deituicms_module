var App=new Vue({
	el:"#App",
	data:{
		item:{},
		logList:[]
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=recycle&a=show&ajax=1",
				data:{
					id:id
				},
				dataType:"json",
				success:function(res){
					that.item=res.data.data;
					that.logList=res.data.logList;
				}
			})
		},
		accept:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=recycle&a=accept&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					
					skyJs.toast(res.message);
					if(!res.error){
						that.getPage();
					}
				}
			})
		},
		send:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=recycle&a=send&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					
					skyJs.toast(res.message);
					if(!res.error){
						that.getPage();
					}
				}
			})
		},
		finish:function(item){
			var that=this;
			skyJs.confirm({
				title:"确认提示",
				content:"确认回收完成吗？",
				success:function(){
					$.ajax({
						url:"/moduleadmin.php?m=recycle&a=finish&ajax=1",
						data:{
							id:item.id
						},
						dataType:"json",
						success:function(res){
							
							skyJs.toast(res.message);
							if(!res.error){
								that.getPage();
							}
						}
					})
				}
			})
		},
		cancel:function(item){
			var that=this;
			skyJs.confirm({
				title:"确认提示",
				content:"确认取消回收吗？",
				success:function(){
					$.ajax({
						url:"/moduleadmin.php?m=recycle&a=cancel&ajax=1",
						data:{
							id:item.id
						},
						dataType:"json",
						success:function(res){
							
							skyJs.toast(res.message);
							if(!res.error){
								that.getPage();
							}
						}
					})
				}
			})
		}
	}
})