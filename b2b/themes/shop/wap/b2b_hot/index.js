var app=new Vue({
	el:"#App",
	data:function(){
		return {
			vipList:[],
			myList:[]
		}
	},
	created:function(){
		this.getPage();
		this.getMy();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=b2b_hot&a=api&ajax=1",
				dataType:"json",
				success:function(res){
					that.vipList=res.data.vipList;
				}
				
			})
		},
		getMy:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=b2b_shop_hotvip&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.myList=res.data.list;
				}
				
			})
		},
		buy:function(vid){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=b2b_shop_hotvip&a=buy&ajax=1",
				dataType:"json",
				data:{
					vid:vid
				},
				success:function(res){
					skyToast(res.message);
					 that.getMy();
				}
				
			})
		},
		goDetail:function(spid){
			window.location="/moduleshop.php?m=b2b_shop_hotvip&a=show&spid="+spid
		}
	}
})