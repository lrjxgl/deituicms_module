var that;
	new Vue({
		el:"#App",
		data:function(){
			return {
				ordercode:"",
				order:{},
				prolist:{},
				isorder:false,
				errorMsg:"",
				finishMsg:""
			}
		},
		created:function(){
			that=this;
			if(ordercode!=""){
				this.ordercode=ordercode;
				this.checkOrder();
			}
		},
		methods:{
			checkOrder:function(){
				
				$.ajax({
					dataType:"json",
					url:"/module.php?m=fsbuy_order_shop&a=CheckOrderSave&ajax=1&ordercode="+this.ordercode,
					success:function(res){
						
						if(res.error){							
							that.errorMsg=res.message;
							return false;
						}
						that.errorMsg="";
						that.isorder=true;
						that.order=res.data.order;
						that.prolist=res.data.prolist;
					}
				})
			},
			finishOrder:function(){
				
				$.ajax({
					dataType:"json",
					url:"/module.php?m=fsbuy_order_shop&a=CheckOrderSave&ajax=1&ordercode="+this.ordercode,
					success:function(res){
						that.finishMsg=res.message;
						if(res.error){
							skyToast(res.message);
							return false;
						}
						that.order.status=3;
						 
						
					}
				})
			}
		}
	})