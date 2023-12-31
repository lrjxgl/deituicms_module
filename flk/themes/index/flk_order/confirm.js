var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			shopid:{},
			pageData:{},
			coupon_id:0,
			ksmClass:"",
			flkChoice:0,
			flkPrice:0,
			flkview:0,
			rules:{},
			flk_discount:0,
			sendtype:"",
			express_money:0,
			paymoney:0
		}
	},
	created:function(){
		this.getPage();
		$("#app").show();
		this.getRule();
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
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_order&a=confirm&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.coupon_id=res.data.coupon_id;
					that.shop=res.data.shop;
					that.flk_discount=parseInt(res.data.flk_discount*100)/100;
					that.pageData.account_money=parseInt(res.data.account_money*100)/100;
					that.sendtype=res.data.sendtype;
					that.express_money=parseInt(res.data.express_money*100)/100;
				}
			})
		},
		changeAddr:function(user_address_id){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_order&a=confirm&ajax=1",
				data:{
					user_address_id:user_address_id
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		},
		changePaytype:function(paytype){
			this.pageData.paytype=paytype;
		},
		setCoupon:function(cid){
			this.coupon_id=cid;
		},
		toggleFlk:function(){
			if(this.flkChoice){
				this.flkChoice=0;
				
				this.pageData.total_money=this.pageData.total_money-this.flkPrice;
				this.flkPrice=0; 
			}else{
				this.flkChoice=1;
				this.flkPrice=parseInt(this.pageData.total_money*this.flk_discount*100)/100;
				this.pageData.total_money=this.pageData.total_money+this.flkPrice;
			 
			}
			
		}
	}
});