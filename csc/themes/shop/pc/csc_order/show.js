new Vue({
	el:"#App",
	data:function(){
		return {
			order:{},
			addr:{},
			prolist:{},
			shop:{},
			sender:{},
			sMoney:0,
			sContent:"",
			showSend:false,
			sds:[],
			senderid:0,
			sType:1
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_order&a=show&ajax=1&orderid="+orderid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.order=res.data.order;
					that.addr=res.data.addr;
					that.prolist=res.data.prolist;
					that.sender=res.data.sender;
					if(res.data.change){
						that.sMoney=res.data.change.money;
						that.sContent=res.data.change.sContent;
					}
					that.sds=res.data.sds;
				}
			})
		},
		cancel:function(orderid){
			var that=this;
			if(confirm("确认取消订单吗？")){
				$.ajax({
					dataType:"json",
					url:"/moduleshop.php?m=csc_order&a=cancel&ajax=1&orderid="+orderid,
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
					url:"/moduleshop.php?m=csc_order&a=confirm&ajax=1&orderid="+orderid,
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
				url:"/moduleshop.php?m=csc_order&a=send&ajax=1&orderid="+orderid,
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
		},
		finish:function(orderid){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/moduleshop.php?m=csc_order&a=finish&ajax=1&orderid="+orderid,
				success:function(res){
					if(res.error){
						return false;
					}
					var order=that.order;
					order.status=3;
					order.status_name="已完成";
					that.order=order;
				}
			})
		},
		changeMoney:function(){
			var that=this;
			if(that.sType==1){
				if(that.sMoney>0){
					skyToast("多还差价只能小于");
					return false;
				}
			}else{
				if(that.sMoney<0){
					skyToast("少补差价只能大于0");
					return false;
				}
			}
			if(confirm("补单只能进行一次，确认无误?")){
				$.ajax({
					dataType:"json",
					url:"/moduleshop.php?m=csc_order&a=changemoney&ajax=1&orderid="+orderid,
					data:{
						typeid:that.sType,
						money:this.sMoney,
						content:this.sContent
					},
					success:function(res){
						if(res.error){
							return false;
						}
						that.getPage(); 
					}
				})
			}
			
		},
		addSender:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_order_send&a=fenpei&ajax=1",
				dataType:"json",
				data:{
					senderid:this.senderid,
					orderid:orderid
				},
				success:function(res){
					skyToast(res.message);
					if(!res.erorr){
						that.showSend=0;
						that.getPage();
					}
				}
			})
		}
	}
})