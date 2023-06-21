 
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			group:{},
			pageLoad:false
		}
	},
	created:function(){
		
		this.getPage();
	},
	methods:{
		goProduct:function(id){
			window.location="/module.php?m=car_product&a=show&productid="+id
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=car_group_product&ajax=1",
				dataType:"json",
				data:{
					gkey:gkey
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
			
		}
	}
	
})