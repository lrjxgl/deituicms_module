var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{},
			pageLoad:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_coupon_user&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		}
	}
})