var app=new Vue({
	el:"#App",
	data:function(){
		return {
			data:[],
			productid:0,
			list:[],
			addrModal:false,
			canBuy:false
		}
	},
	created:function(){
		var that=this;
		this.productid=productid;
		this.getPage();
 
	},
	methods:{
		setProduct:function(id){
			this.productid=id;
			this.getPage();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxa_product&a=show&ajax=1&id="+this.productid,
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
					that.list=res.data.list; 
					that.canBuy=res.data.canBuy;
				}
			})
		},
		order:function(){
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url: "/module.php?m=fxa_order&a=order&ajax=1&productid=" + productid,
				dataType: "json",
				type:"POST",
				data:{
					productid:productid,
					nickname:$("#nickname").val(),
					telephone:$("#telephone").val(),
					address:$("#address").val()
				},
				success: function(res) {
					
					skyToast(res.message);
					if(res.error){
						return false;
					}
					window.location=res.data.payurl;
				}
			})
		}
	}
})