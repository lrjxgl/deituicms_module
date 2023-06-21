var isFirst=true,per_page=0;
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			cityList:[],
			shop:{},
			cityid:0,
			lat:0,
			lng:0,
			gpsType:"gps",
			GpsIng:false
		}
	},
	created:function(){
		var that=this;
		this.catid=catid;
		if (navigator.geolocation){
			this.GpsIng=true;
			skyToast("正在定位")
			navigator.geolocation.getCurrentPosition(function(position){
				skyToast("定位成功");
				that.lat=position.coords.latitude;
				that.lng=position.coords.longitude;
				
				that.GpsIng=false;
				that.getPage();
			},function(e){
				skyToast(e.message);
				that.GpsIng=false;
				that.getPage();
			});
			
		}else{
			skyToast("无法定位")
			this.getPage();
		}
		
		
	},	
	methods:{
		goShop:function(shopid){
			$.ajax({
				url:"/module.php?m=csc&set_shopid="+shopid,
				success:function(res){
					window.history.back();
				}
			})
			//window.location="/module.php?m=csc&set_shopid="+shopid
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_shoplist&a=list&type=near&ajax=1",
				data:{
					catid:that.catid,
					filter:this.filter,
					orderby:this.order,
					sc_id:this.sc_id,
					cityid:this.cityid,
					lat:this.lat,
					lng:this.lng,
					gpsType:this.gpsType
				},
				dataType:"json",
				success:function(res){
					per_page=res.data.per_page;
					isFirst=false;
					that.pageData=res.data;
					that.pageLoad=true;
					that.shop=res.data.shop;
					that.cityList=res.data.cityList;
					that.cityid=res.data.cityid;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_shoplist&a=list&type=near&ajax=1",
				data:{
					catid:that.catid,
					filter:this.filter,
					orderby:this.order,
					sc_id:this.sc_id,
					cityid:this.cityid,
					lat:this.lat,
					lng:this.lng,
					gpsType:this.gpsType
				},
				dataType:"json",
				success:function(res){
					per_page=res.data.per_page;
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		setCity:function(id){
			this.cityid=id;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		}
		
	}
});