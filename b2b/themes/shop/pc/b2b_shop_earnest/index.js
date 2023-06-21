var App=new Vue({
	el:"#App",
	data:function(){
		return {
			moneyList:[],
			data:{},
			money:0,
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=b2b_shop_earnest&ajax=1",
				dataType:"json",
				 
				success:function(res){
					 
					if(res.error){
						return false;
					}
					that.moneyList=res.data.moneyList;
					that.data=res.data.data;
					that.list=res.data.list;
				}
			})
		},
		save:function(){
			var that=this;
			skyJs.confirm({
				content:"确认支付保证金"+that.money+"元吗?",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=b2b_shop_earnest&a=save&ajax=1",
						dataType:"json",
						data:{
							money:that.money
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