var App=new Vue({
	el:"#App",
	data:function(){
		return {
			placeid:0,
			tab:"activity",
			actList:[],
			orderList:[],
			placeList:[] 
		}
	},
	created:function(){
	 
		this.getActList();
		this.getPlaceList();
		this.getOrderList();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		getActList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_activity&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.actList=res.data.list;
				}
			})
		},
		goActivity:function(item){
			window.location="/module.php?m=fishing_free_activity&a=show&actid="+item.actid
		},
		getPlaceList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_place&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.placeList=res.data.list;
				}
			})
		},
		goPlace:function(item){
			window.location="/module.php?m=fishing_free_place&a=show&placeid="+item.placeid
		},
		getOrderList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_order&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.orderList=res.data.list;
				}
			})
		}
		 
	}
})