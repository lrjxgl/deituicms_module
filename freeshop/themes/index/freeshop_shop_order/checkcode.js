var app=new Vue({
	el:"#App",
	data:function(){
		return {
			code:"",
			order:{},
			product:{},
			ordercode:{},
			showOrder:false,
			checkSuccess:false
		}
	},
	methods:{
		search:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_shop_order&a=CodeOrder&ajax=1",
				dataType:"json",
				method:"POST",
				data:{
					code:this.code
				},
				success:function(res){
					that.checkSuccess=false;
					if(res.error){
						skyToast(res.message);
						that.showOrder=false;
						return false;
					}
					that.showOrder=true;
					that.ordercode=res.data.ordercode; 
					that.order=res.data.order;
					that.product=res.data.product;
				}
			})
		},
		check:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=freeshop_shop_order&a=CodeFinish&ajax=1",
				dataType:"json",
				method:"POST",
				data:{
					code:this.code
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						
						return false;
					}
					that.showOrder=false;
					that.checkSuccess=true;
					console.log(that.showOrder)
					that.order=res.data.order;
				 
				}
			})
		}
	}
})