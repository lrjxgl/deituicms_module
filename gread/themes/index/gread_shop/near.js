var isgps=false,lat,lng;
var App;
var geolocation = new BMap.Geolocation();
geolocation.getCurrentPosition(function(r){
	if(this.getStatus() == BMAP_STATUS_SUCCESS){
		lat=r.point.lat;
		lng=r.point.lng;
		isgps=true;
		App.getList();
	}
	else {
		skyJs.toast("无法获取位置信息");
	}        
},{enableHighAccuracy: true})
App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			pageLoad:true
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gread_shop&a=neardata&ajax=1",
				data:{
					lat:lat,
					lng:lng
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gread_shop&a=neardata&ajax=1",
				data:{
					lat:lat,
					lng:lng
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goShop:function(shopid){
			$.ajax({
				url:"/module.php?m=gread_shop&a=setshop&ajax=1",
				dataType:"json",
				data:{
					shopid:shopid
				},
				success:function(res){
					window.location="/module.php?m=gread_shop"
				}
			})
			
		}
	}
})