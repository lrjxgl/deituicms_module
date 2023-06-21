var App=new Vue({
	el:"#App",
	data:function(){
		return {
			order:{},
			orderid:0
		}
	},
	created:function(){
		this.orderid=orderid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_dache_order&a=show&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.order=res.data.order;
				}
			})
		},
		finishOrder:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_dache_order&a=finish&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					skyToast(res.message)
					that.getPage();
				}
			})
		}
	}
	
})