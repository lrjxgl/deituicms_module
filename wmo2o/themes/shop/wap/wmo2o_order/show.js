new Vue({
	el:"#App",
	data:function(){
		return {
			order:{},
			addr:{},
			prolist:{},
			shop:{},
			paotui:{},
			paotui_money:3,
			showPaotui:false,
			acToken:""
		}
	},
	created:function(){
		this.getPage();
		
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=wmo2o_order&a=show&ajax=1&orderid="+orderid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.order=res.data.order;
					that.addr=res.data.addr;
					that.prolist=res.data.prolist;
					that.acToken=res.data.acToken;
					if(that.order.paotui_id){
						that.getPaotui();
					}
				}
			})
		},
		cancel:function(orderid){
			var that=this;
			if(confirm("确认取消订单吗？")){
				$.ajax({
					dataType:"json",
					url:"/moduleshop.php?m=wmo2o_order&a=cancel&ajax=1&orderid="+orderid,
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
					url:"/moduleshop.php?m=wmo2o_order&a=confirm&ajax=1&orderid="+orderid,
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
				url:"/moduleshop.php?m=wmo2o_order&a=send&ajax=1&orderid="+orderid,
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
		paotuiPost:function(orderid){
			var that=this;
			if(that.paotui_money<1){
				skyToast("跑腿金额过低");
				return false;
			}
			$.ajax({
				dataType:"json",
				url:"/module.php?m=paotui_fromapi&a=post&ajax=1",
				method:"POST",
				data:{
					tablename:"wmo2o",
					orderid:orderid,
					money:that.paotui_money,
					acToken:that.acToken
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.showPaotui=false;
					that.getPage();
				}
			})
		},
		getPaotui:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=paotui_fromapi&a=get&ajax=1",
				method:"POST",
				data:{
					tablename:"wmo2o",
					orderid:orderid,
					acToken:that.acToken
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.paotui=res.data.paotui;
				}
			})
		},
		paotuiFinish:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=paotui_fromapi&a=finish&ajax=1",
				method:"POST",
				data:{
					tablename:"wmo2o",
					orderid:orderid,
					acToken:that.acToken
					 
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.paotui=res.data.paotui;
				}
			})
		}
	}
})