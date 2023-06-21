var App=new Vue({
	el:"#App",
	data:function(){
		return {
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
				url:"/module.php?m=household_order_append&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		pay:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=household_order_append&a=pay&ajax=1",
				dataType:"json",
				data:{
					orderid:item.orderid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					window.location=res.data.payurl;
					 
				}
			})
		}
	}
})