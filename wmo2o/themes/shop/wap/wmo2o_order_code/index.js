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
					url:"/moduleshop.php?m=wmo2o_order_code&a=check&ajax=1&ordercode="+this.ordercode,
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
					url:"/moduleshop.php?m=wmo2o_order_code&a=finish&ajax=1&ordercode="+this.ordercode,
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