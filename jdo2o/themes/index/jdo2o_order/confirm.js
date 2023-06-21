var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			coupon_id:0
		}
	},
	created:function(){
		this.getPage();
		$("#app").show();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=jdo2o_order&a=confirm&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.coupon_id=res.data.coupon_id;
				}
			})
		},
		changeAddr:function(user_address_id){
			var that=this;
			$.ajax({
				url:"/module.php?m=jdo2o_order&a=confirm&ajax=1",
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
		}
	}
});