var App=new Vue({
	el:"#App",
	data:function(){
		return {
			orderid:0,
			order:{},
			product:{},
			 
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
				url:"/module.php?m=ershou_shop_order&a=show&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.order=res.data.order;
					that.product=res.data.product;
				}
				
			})
		},
		confirm:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_shop_order&a=confirm&ajax=1",
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					that.order.status=1;
				}
				
			})
		},
		send:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_shop_order&a=send&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					orderid:this.orderid,
					express:that.order.express
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.order.status=2;
				}
				
			})
		}
	}
})