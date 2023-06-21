var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			page:"order"
		}
	},
	created:function(){
		this.getOrder();
	},
	methods:{
		setPage:function(page){
			this.page=page;
			switch(page){
				case "order":
					this.getOrder();
					break;
				case "view":
					this.getView();
					break;
			}
		},
		getOrder:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gxny_shop_user&a=order&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					
				}
			})
		},
		getView:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gxny_shop_user&a=view&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					
				}
			})
		}
	}
})