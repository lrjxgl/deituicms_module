var App=new Vue({
	el:"#App",
	data:function(){
		return {
			group:{},
			list:[],
			line:[],
			logList:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_group&a=show&ajax=1",
				dataType:"json",
				data:{
					gid:gid
				},
				success:function(res){
					that.group=res.data.group;
					that.list=res.data.list;
					that.line=res.data.line;
				}
			})
			this.getLog();
		},
		getLog:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_group_log&ajax=1",
				dataType:"json",
				data:{
					gid:gid
				},
				success:function(res){
					that.logList=res.data.list;
				}
			})
		},
		uSend:function(orderid){
			var that=this;
			skyJs.confirm({
				content:"确认接到乘客了吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_order&a=send&ajax=1",
						dataType:"json",
						data:{
							orderid:orderid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})
		},
		uCancel:function(orderid){
			var that=this;
			skyJs.confirm({
				content:"确认接到乘客了吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_order&a=cancel&ajax=1",
						dataType:"json",
						data:{
							orderid:orderid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})	
			
		},
		uFinish:function(orderid){
			var that=this;
			skyJs.confirm({
				content:"确认乘客到站下车吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_order&a=finish&ajax=1",
						dataType:"json",
						data:{
							orderid:orderid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})	
			
		},
		cancel:function(gid){
			var that=this;
			skyJs.confirm({
				content:"取消订单会扣除业绩吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_group&a=cancel&ajax=1",
						dataType:"json",
						data:{
							gid:gid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})	
			
		},
		confirm:function(gid){
			var that=this;
			skyJs.confirm({
				content:"确认接单，请尽快接客上车",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_group&a=confirm&ajax=1",
						dataType:"json",
						data:{
							gid:gid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})	
			
		},
		finish:function(gid){
			var that=this;
			skyJs.confirm({
				content:"确认乘客都接送完了吗",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_group&a=finish&ajax=1",
						dataType:"json",
						data:{
							gid:gid
						},
						success:function(res){
							skyJs.toast(res.message)
							that.getPage();
						}
					})
				}
			})	
			
		},
	}
	
})