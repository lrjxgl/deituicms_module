new Vue({
	el:"#App",
	data:function(){
		return {
			order:{},
			addr:{},
			prolist:{},
			shop:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=jdo2o_order&a=show&ajax=1&orderid="+orderid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.order=res.data.order;
					that.addr=res.data.addr;
					that.prolist=res.data.prolist;
				}
			})
		},
		cancel:function(orderid){
			var that=this;
			if(confirm("确认取消订单吗？")){
				$.ajax({
					dataType:"json",
					url:"/moduleshop.php?m=jdo2o_order&a=cancel&ajax=1&orderid="+orderid,
					success:function(res){
						if(res.error){
							return false;
						}
						var order=that.order;
						order.status=4;
						order.status_name="已取消";
						that.order=order;
					}
				})
			}
		},
		confirm:function(orderid){
			var that=this;
			if(confirm("确认接单吗?")){
				$.ajax({
					dataType:"json",
					url:"/moduleshop.php?m=jdo2o_order&a=confirm&ajax=1&orderid="+orderid,
					success:function(res){
						if(res.error){
							return false;
						}
						var order=that.order;
						order.status=1;
						order.status_name="已确认";
						that.order=order;
					}
				})
			} 
					
			 
		},
		send:function(orderid){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/moduleshop.php?m=jdo2o_order&a=send&ajax=1&orderid="+orderid,
				success:function(res){
					if(res.error){
						return false;
					}
					var order=that.order;
					order.status=2;
					order.status_name="待收货";
					that.order=order;
				}
			})
		}
	}
})