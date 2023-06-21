var App=new Vue({
	el:"#App",
	data:function(){
		return {
		 
			data:{},
			money:0,
			bankList:[],
			bankid:0,
			paypwd:""
		}
	},
	created:function(){
		this.getPage();
	},
	watch:{
		money:function(n,o){
			if(n>this.data.money){
				this.money=this.data.money;
			}
		}
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=wmo2o_shop_earnest&a=out&ajax=1",
				dataType:"json",
				 
				success:function(res){
					 
					if(res.error){
						return false;
					}
					that.bankList=res.data.bankList;
					console.log(that.bankList)
					that.data=res.data.data;
				}
			})
		},
		save:function(){
			var that=this;
			if(that.money<=0){
				skyJs.toast("请填写金额")
				return false;
			}
			if(that.money>that.data.money){
				skyJs.toast("保证金金额不足")
				return false;
			}
			if(that.bankid==0){
				skyJs.toast("请选择银行卡");
				return false;
			}
			skyJs.confirm({
				content:"确认提出保证金"+that.money+"元吗?",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=wmo2o_shop_earnest&a=outsave&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							money:that.money,
							bankid:that.bankid,
							paypwd:that.paypwd
						},
						success:function(res){
							skyToast(res.message);
							if(res.error){
								return false;
							}
							that.getPage();
						}
					})
				}
			})
			
		}
	}
})