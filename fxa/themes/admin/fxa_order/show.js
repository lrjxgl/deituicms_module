var App=new Vue({
	el:"#App",
	data:function(){
		return {
			orderid:0,
			data:{},
			express_no:""
		}
	},
	created:function(){
		this.orderid=orderid
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=fxa_order&a=show&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.data=res.data.data;
					that.express_no=res.data.data.express_no;
				}
				
			})
		},
		send:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=fxa_order&a=send&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.getPage();
				}
				
			})
		},
		finish:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=fxa_order&a=finish&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.getPage();
				}
				
			})
		},
		updateExpress:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=fxa_order&a=updateExpress&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					orderid:this.orderid,
					express_no:this.express_no
				},
				success:function(res){
					skyToast(res.message)
					that.getPage();
				}
				
			})
		}
	}
})