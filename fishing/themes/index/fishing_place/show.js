
var app=new Vue({
	el:"#App",
	data:function(){
		return {
			tab:"blog",
			ckList:[],
			ckFirst:true,
			ckPage:0,
			blogList:[],
			blogFirst:true,
			blogPage:0,
			shModal:false,
			tagContent:"",
			placeid:0,
			isFav:0
		}
	},
	created:function(){
		this.placeid=placeid
		this.getBlog();
		this.getCheckin();
		this.getIsFav();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		getCheckin:function(){
			var that=this;
			if(this.ckPage==0 && !this.ckFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_checkin&a=list&ajax=1",
				data:{
					placeid:placeid,
					per_page:this.ckPage
				},
				dataType:"JSON",
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.ckPage=res.data.per_page; 
					if(that.ckFirst){
						that.ckFirst=false;
						that.ckList=res.data.list;
					}else{
						for(var i in res.data.list){
							that.ckList.push(res.data.list[i])
						}
						
					}
				}
			})
		},
		getBlog:function(){
			var that=this;
			if(this.blogPage==0 && !this.blogFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_blog&a=list&ajax=1",
				data:{
					placeid:placeid,
					per_page:this.blogPage
				},
				dataType:"JSON",
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					if(that.blogFirst){
						that.blogFirst=false;
						that.blogList=res.data.list;
					}else{
						for(var i in res.data.list){
							that.blogList.push(res.data.list[i])
						}
						
					}
					that.blogPage=res.data.per_page;
					
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=fishing_blog&a=show&id="+id
		},
		tagSubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_place_utag&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:this.tagContent,
					placeid:this.placeid
				},
				success:function(res){
					skyJs.toast(res.message);
					that.shModal=false;
				}
			})
		},
		showMap:function(){
			$("#bdmapModal").addClass("bdmapShow")
		},
		getIsFav:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_fav&a=get&ajax=1",
				data:{
					placeid:placeid
				},
				dataType:"JSON",
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.isFav=res.data.isFav;
				}
			})
		},
		toggleFav:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_fav&a=toggle&ajax=1",
				data:{
					placeid:placeid
				},
				dataType:"JSON",
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.isFav=res.data.isFav;
				}
			})
		},
		goHome:function(userid){
			window.location="/module.php?m=fishing_home&userid="+userid
		}
	}
});

var map = new BMapGL.Map("bdmap");
 map.setMapType(BMAP_EARTH_MAP);
  map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
      var scaleCtrl = new BMapGL.ScaleControl();  // 添加比例尺控件
      map.addControl(scaleCtrl);
      var zoomCtrl = new BMapGL.ZoomControl();  // 添加比例尺控件
      map.addControl(zoomCtrl);   
var point = new BMapGL.Point(lng, lat);   
map.centerAndZoom(point, 15); 
var marker1 = new BMapGL.Marker(new BMapGL.Point(lng, lat));
map.addOverlay(marker1); 
var geolocation = new BMapGL.Geolocation();
geolocation.getCurrentPosition(function(r){
	if(this.getStatus() == BMAP_STATUS_SUCCESS){
		//var mk = new BMapGL.Marker(r.point);
		var driving = new BMapGL.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
		driving.search(r.point, point);
	}
	else {
		function myFun(result){
			 
				var driving = new BMapGL.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
				driving.search(result.center, point);
				 
		    }
		    var myCity = new BMapGL.LocalCity();
		    myCity.get(myFun);
	}        
});