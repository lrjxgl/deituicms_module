var app=new Vue({
	el:"#raty-box",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			orderid:0,
			order:{},
			show:0
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
				url:"/module.php?m=exue_order&a=raty&ajax=1&orderid="+this.orderid,
				dataType:"json",
				success:function(res){
					that.pageLoad=true; 
					that.order=res.data.order;
				}	
			})
		},
		ratySubmit:function(e){
			var that=this;
		 
			$.ajax({
				url:"/module.php?m=exue_order&a=ratysave&ajax=1",
				data:$("#ratyForm").serialize(),
				method:"POST",
				dataType:"json",
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					} 
					that.show=0;
				}	
			})
		}
	}
})