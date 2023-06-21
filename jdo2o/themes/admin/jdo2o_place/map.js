//var lat, lng;
// 百度地图API功能
var map;
var mapLoad = false;
//mapInit();
function mapInit() {
	var st=$(window).scrollTop();
	console.log(st);
	$(".baiduMap").show();
	address=$("#address").val();
	mapLoad = true;
	map = new BMap.Map("mapCanvas");
	//map.setMapType(BMAP_HYBRID_MAP)
	// 添加带有定位的导航控件
	var navigationControl = new BMap.NavigationControl({
		// 靠左上角位置
		anchor: BMAP_ANCHOR_TOP_LEFT,
		// LARGE类型
		type: BMAP_NAVIGATION_CONTROL_LARGE,
		// 启用显示定位
		enableGeolocation: true
	});
	map.addControl(navigationControl);
	var nowAddress = '';

	map.addEventListener("click", showInfo);
	//如果未选择地址 则定位当前地址
	if(lat!="0"){
		console.log("定位中");
		var marker = new BMap.Marker(new BMap.Point(lng, lat)); // 创建标注
		map.addOverlay(marker);
		map.centerAndZoom(new BMap.Point(lng, lat), 16);
	}else if(address!=""){
		console.log("根据地址获取");
		$.ajax({
			type:"get",
			url:'https://api.map.baidu.com/geocoder/v2/?address='+address+'&output=json&ak=F73283d678ec76619500152b1a0835c0&callback=showLocation',
			dataType: "jsonp", //指定服务器返回的数据类型
			success:function(res){
				console.log(res);
				skyToast("定位成功"); 
				var latlng=res.result.location;
				lat=latlng.lat;
				lng=latlng.lng;
				$("#lat").val(lat);
				$("#lng").val(lng);
				 map.centerAndZoom(new BMap.Point(latlng.lng, latlng.lat), 11);
				 var marker = new BMap.Marker(new BMap.Point(latlng.lng, latlng.lat)); 
				 map.addOverlay(marker);
			}
		})						
							
	}else{
		var geolocation = new BMap.Geolocation();
		geolocation.getCurrentPosition(function(r) {
			if(this.getStatus() == BMAP_STATUS_SUCCESS) {
				lat = r.point.lat;
				lng = r.point.lng;
				 
				var marker = new BMap.Marker(r.point); // 创建标注
				map.addOverlay(marker);
				map.centerAndZoom(new BMap.Point(lng, lat), 16);
			} else {
				skyToast('获取定位失败' + this.getStatus());
			}
		}, {
			enableHighAccuracy: true
		})
	} 
	//end
}

function showInfo(e) {
	var marker = new BMap.Marker(e.point); // 创建标注
	lat=e.point.lat;
	lng=e.point.lng;
	 
	map.clearOverlays(); 
	nowAddress = e;
	map.addOverlay(marker); // 将标注添加到地图中
	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}
$(document).on("click", ".js-map-show", function() {
	var st=$(window).scrollTop();
	var wh=$(window).height();
	 
	$(".baiduMap").css({top:(st+100)+"px"});
	if(!mapLoad) {
		mapInit();
	}else{
		$(".baiduMap").show();
	}
	
	

})

//确定选择地址
function sureAddressFn() {
 
	if(lat==0 && lng==0 ) {
		skyToast('选择地址不能为空！');
		return;
	}
	$("#lat").val(lat);
	$("#lng").val(lng);
	hideMap();
}

//显示百度地图
function showMap() {
	 
	$('.baiduMap').show();
	 
}
//关闭百度地图
function hideMap() {
	$('.baiduMap').hide();
	 
}