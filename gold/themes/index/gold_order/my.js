var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			addrModalClass:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gold_order&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					 
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		goProduct:function(id){
			window.location="/module.php?m=gold_product&a=show&id="+id
		},
		finish:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=gold_order&a=finish&ajax=1",
				dataType:"json",
				data:{
					orderid:item.orderid
				},
				success:function(res){
					if(res.error){
						uni.showToast({
							title:res.message,
							icon:"none"
						})
						return false;
					}
					item.status=3; 
					 
				}
			})
		}
		 
	}
})