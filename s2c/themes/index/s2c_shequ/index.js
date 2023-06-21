var GPS={
	expire:600,
	set:function(v){
		v.expire=Date.parse(new Date())/1000+this.expire;
		var str=JSON.stringify(v);
		localStorage.setItem("gps",str);
	},
	get:function(){
		var v=localStorage.getItem("gps");
		var json=JSON.parse(v);
		if(!json){
			return false;
		}
		var time=Date.parse(new Date())/1000;
		if(json.expire<time){
			return false;
		}else{
			return json;
		}
	}
}
var geolocation = new BMap.Geolocation();

var lat,lng;


var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			show:"flex",
			keyword:"",
			keywordold:""
		}
	},
	created:function(){
		var latlng=GPS.get();
		console.log(latlng);
		if(latlng){
			lat=latlng.lat;
			lng=latlng.lng;
			this.getPage();
		}else{
			this.getGps();
		}
		
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=s2c_shequ&a=data&ajax=1",
				data:{
					lat:lat,
					lng:lng
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					console.log("pageLoad");
					that.pageData=res.data;
				}
			})
		},
		searchShequ:function(){
			var that=this;
			if(that.keywordold==that.keyword){
				return false;
			}
			that.keywordold=that.keyword;
			$.ajax({
				url:"/module.php?m=s2c_shequ&a=data&ajax=1",
				data:{
					lat:lat,
					lng:lng,
					keyword:that.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
			
		},
		setScid:function(scid){
			$.ajax({
				url:"/module.php?m=s2c_shequ&a=set&scid="+scid+"&ajax=1",
				dataType:"json",
				success:function(res){
					window.location="/module.php?m=s2c"
				}
			})
		},
		getGps:function(){
			var that=this;
			geolocation.getCurrentPosition(function(r){
			if(this.getStatus() == BMAP_STATUS_SUCCESS){
				lat=r.point.lat;
				lng=r.point.lng;
				GPS.set({
					lat:lat,
					lng:lng
				});
				that.getPage();
			}
			else {
				skyToast('获取GPS失败'+this.getStatus());
			}        
			},{enableHighAccuracy: true})
		}
		
	}
})