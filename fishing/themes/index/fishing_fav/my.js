var app=new Vue({
	el:"#App",
	data:function(){
		return {
			 
			placeList:[],
			 
		}
	},
	created:function(){
		 
		this.getPlace();
		 
	},
	methods:{
		 
		getPlace:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_fav&a=my&ajax=1",
				dataType:"JSON",
		 
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.placeList=res.data.list;
				}
			})
		},
		goPlace:function(placeid){
			window.location="/module.php?m=fishing_place&a=show&placeid="+placeid;
		},
		 
	}
})