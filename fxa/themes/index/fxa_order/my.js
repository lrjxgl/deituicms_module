var App=new Vue({
	el:"#App",
	data:function(){
		return {
			isFirst:true,
			per_page:0,
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
				url:"/module.php?m=fxa_order&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goDetail:function(orderid){
			window.location="/module.php?m=fxa_order&a=show&orderid="+orderid
		}
	}
})