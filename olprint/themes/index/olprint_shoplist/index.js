 
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			catid:0,
			type:"fav",
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		 
		 
	},	
	methods:{
		goShop:function(shopid){
			window.location="/module.php?m=olprint_shop&shopid="+shopid;
			
		},
		choiceShop:function(shopid){
			$.ajax({
				url:"/module.php?m=olprint_shop&a=choice&ajax=1&shopid="+shopid,
				dataType:"json",
				success:function(res){
					goBack()
					//window.location="/module.php?m=olprint"
				}
			})
			
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=olprint_shoplist&a=list&ajax=1",
				data:{
					lat:lat,
					lng:lng
				}, 
				dataType:"json",
				success:function(res){
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.list=res.data.list;
					that.pageLoad=true;
					
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=olprint_shoplist&a=list&ajax=1",
				data:{
					per_page:that.per_page,
					lat:lat,
					lng:lng
				},
				dataType:"json",
				success:function(res){
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.list=res.data.list;
					that.pageLoad=true;
				}
			})
		}
		 
	}
});