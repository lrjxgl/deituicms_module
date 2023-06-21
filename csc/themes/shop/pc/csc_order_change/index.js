
var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:[],
			type:type
		}
	},
	created:function(){
		this.getPage();
		$("#app").show();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_order_change&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		},
		goOrder:function(orderid){
			window.location="/moduleshop.php?m=csc_order&a=show&orderid="+orderid
		},
		setType:function(t){
			this.type=t;
			this.getPage();
		} 
	}
});