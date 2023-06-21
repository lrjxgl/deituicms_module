var App=new Vue({
	el:"#App",
 
	data:function(){
		return {
			pageLoad:false,
			shop:{}, 
			data:{},
			canbuy:0,
			addr:{},
			showBuy:false,
			showFlk:false,
			flkChoice:0,
			flkPrice:0,
			flkview:0,
			rules:{},
			flk_discount:0,
			total_money:0,
			account_money:0,
			pay_money:0,
			addr:{},
			tab:"detail",
			queList:[]
		}
	},
	created:function(){
		this.getPage();
		this.getRule();
		this.getQueList();
	},
	methods:{
		getRule:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk&a=rule",
				dataType:"json",
				success:function(res){
					that.rules=res;
				}
			})
		},
		getQueList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=queue&ajax=1",
				dataType:"json",
				data:{
					productid:id
				},
				success:function(res){
					that.queList=res.data.list;
					 
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=show&ajax=1",
				dataType:"json",
				data:{
					id:id
				},
				success:function(res){
					that.pageLoad=true;
					that.data=res.data.data;
					that.shop=res.data.shop;
					that.canbuy=res.data.canbuy;
					that.total_money=res.data.data.one_price;
					that.account_money=res.data.account_money;
					that.pay_money=res.data.pay_money;
					that.addr=res.data.addr;
				}
			})
		},
		goBuy:function(){
			this.showBuy=true;
			console.log("showBuy")
		},
		buy:function(){
			
		},
		toggleFlk:function(){
			if(this.flkChoice){
				this.flkChoice=0;
				
				this.total_money=this.data.one_price;
				this.pay_money=Math.max(0,this.total_money-this.account_money)
				this.flkPrice=0; 
			}else{
				this.flkChoice=1;
				 
				this.total_money=parseInt(this.data.one_price*1.1*100)/100;
				this.pay_money=Math.max(0,parseInt((this.total_money-this.account_money))*100/100)
			 
			}
			
		},
		orderSubmit:function(){
			var data=$("#oForm").serialize();
			$.ajax({
				type:"POST",
				dataType:"json",
				url:"/module.php?m=flk_order&a=order_one&ajax=1",
				data:data,
				success:function(res){
					skyToast(res.message);
					if(!res.error){
						if(res.data.action=='pay'){
							window.location=res.data.payurl;
						}else if(res.data.action=="finish"){
							window.location="/module.php?m=flk_order&a=success&orderid="+res.data.orderid;
						}
					}
				}
			})
		}
		 
	},
	 
})