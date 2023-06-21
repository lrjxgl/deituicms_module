var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			product:{},
			order:{},
			orderid:0,
			raty:{
				raty_grade:10,
				content:""
			}
		}
	},
	created:function(){
		this.orderid=orderid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_order&a=raty&ajax=1&orderid="+this.orderid,
				dataType:"json",
				success:function(res){
					console.log(res)
					 
					that.order=res.data.order;
					that.product=res.data.product;
					that.pageLoad=true;
					if(res.data.raty){
						that.raty=res.data.raty;
					}
				}	
			})
		},
		ratySubmit:function(e){
			var that=this;
		 
			$.ajax({
				url:"/module.php?m=freeshop_order&a=ratysave&ajax=1",
				data:$("#ratyForm").serialize(),
				method:"POST",
				dataType:"json",
				success:function(rs){
					 
					that.getPage();
				}	
			})
		}
	}
})