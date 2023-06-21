var App=new Vue({
	el:"#App",
	data:function(){
		return {
			money:0,
			content:"",
			shopid:0,
			shop:{}
		}
	},
	created:function(){
		this.shopid=shopid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=wmo2o_offpay&ajax=1",
				dataType:"json",
			 
				data:{
					 
					shopid:this.shopid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					
					that.shop=res.data.shop;
				}
			})
		},
		submit:function(){
			var that=this;
			if(this.money<=0){
				return false;
			}
			skyJs.confirm({
				title:"买单提示",
				content:"确认向商家支付"+this.money+"元吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=wmo2o_offpay&a=order&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							money:that.money,
							content:that.content,
							shopid:that.shopid
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