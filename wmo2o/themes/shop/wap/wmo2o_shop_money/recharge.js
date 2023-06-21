var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pay_type_list:[],
			money:0,
			paytype:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=wmo2o_shop_money&a=recharge&ajax=1",
				dataType:"json",
				success:function(res){
					that.pay_type_list=res.data.pay_type_list;
				}
			})
		},
		save:function(){
			var that=this;
			if(that.money<=0){
				skyJs.toast("请填写金额")
				return false;
			}
			 
			if(that.paytype==""){
				skyJs.toast("请选择支付方式");
				return false;
			}
			skyJs.confirm({
				content:"确认充值"+that.money+"元吗?",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=wmo2o_shop_money&a=rechargesave&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							money:that.money,
						 
							paytype:that.paytype
						},
						success:function(res){
							skyToast(res.message);
							if(res.error){
								return false;
							}
							window.location=res.data.payurl;
						}
					})
				}
			})
			
		}
	}
})