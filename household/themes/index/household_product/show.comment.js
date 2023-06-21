var cmVue=new Vue({
	el:"#cmApp",
	data:function(){
		return {
			rscount:{},
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
				url:"/module.php?m=household_product&a=raty&ajax=1&limit=1&id="+productid,
				dataType:"json",
				success:function(res){
					that.rscount=res.data.rscount;
					that.list=res.data.list;
				}
			})
		}
	}
})