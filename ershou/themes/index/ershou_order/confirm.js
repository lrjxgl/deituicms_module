var app=new Vue({
	el:"#App",
	data:function(){
		return {
			data:[],
			productid:0,
			addrList:[],
			total_money:0,
			user_address_id:0,
			bjid:0,
			baojia:{}
		}
	},
	created:function(){
		var that=this;
		this.productid=productid;
		this.bjid=bjid;
		this.getPage();
 
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_order&a=confirm&ajax=1&productid="+this.productid,
				data:{
					bjid:this.bjid
				},
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
					that.addrList=res.data.addrList; 
					that.total_money=res.data.total_money;
					that.user_address_id=res.data.user_address_id;
					that.baojia=res.data.baojia;
				}
			})
		},
		order:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_order&a=order&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					productid:this.productid,
					user_address_id:this.user_address_id,
					bjid:this.bjid
				},
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					}
					if(res.data.action=='pay'){
						window.location=res.data.payurl
					}else{
						goBack();
					}
				}
			})
		}
	}
})