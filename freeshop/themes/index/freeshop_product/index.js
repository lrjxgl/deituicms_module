var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.getPage();
		},
		goBlog:function(id){
			window.location="/module.php?m=freeshop_product&a=show&productid="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_product&a=list&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		}
	}
})