var map = new BMapGL.Map("bdmap");
map.setMapType(BMAP_EARTH_MAP);
map.enableScrollWheelZoom(true); //开启鼠标滚轮缩放
var scaleCtrl = new BMapGL.ScaleControl(); // 添加比例尺控件
map.addControl(scaleCtrl);

// 创建定位控件
        var locationControl = new BMapGL.LocationControl({
            // 控件的停靠位置（可选，默认左上角）
            anchor: BMAP_ANCHOR_TOP_LEFT,
            // 控件基于停靠位置的偏移量（可选）
            offset: new BMapGL.Size(20, 20)
        });
        // 将控件添加到地图上
        map.addControl(locationControl);
var point = new BMapGL.Point(120.294795, 27.3151329);
map.centerAndZoom(point, 13);
var markers=[];
function getPlace(){
	$.ajax({
		url:"/module.php?m=fishing_place&ajax=1",
		dataType:"json",
		success:function(res){
			for(var i in res.data.list){
				var mp=res.data.list[i];
				markers[i]= new BMapGL.Marker(new BMapGL.Point(mp.lng, mp.lat));
				map.addOverlay(markers[i]); 
				clickInfo(i,mp,point)
			}
		}
	})
	console.log(markers);
}
function clickInfo(i,place,point){
	var allowStr="";
	if(place.is_allow==0){
		allowStr='<div class="cl-primary">允许</div>';
	}else if(place.is_allow==1){
		allowStr='<div class="cl-warning">限制</div>';
	}else{
		allowStr='<div class="cl-danger">禁止</div>';
	}
	var sContent=`
		<div style="width:300px;height:140px;padding:10px;z-index:999;position:relative">
			<div class="cl-red mgb-5">`+place.title+`</div>
			<div class="mgb-5 cl2">`+place.description+`</div>
			<div class="flex">
				<div class="mgr-5">`+allowStr+`</div>
				<div>`+place.tags+`</div>
				 
			</div>
			<div class="none">
				<div class="flex-1"></div>
				<a class="cl-primary mgr-10" href="/module.php?m=fishing_place&a=show&placeid=`+place.placeid+`">去看看</a>
			</div>
			
		</div>
	`;
	var opts = {
		width: 350,
		height: 140,
		title: '钓点详情'
	};
	var infoWindow = new BMapGL.InfoWindow(sContent,opts);
	infoWindow.disableCloseOnClick()
	infoWindow.addEventListener("close",function(){
		$("#goPlace").hide();
	})
	markers[i].addEventListener('click', function(){
	    this.openInfoWindow(infoWindow,point);
		console.log(place)
		$("#goPlace").show().attr("href","/module.php?m=fishing_place&a=show&placeid="+place.placeid);
	});
	
}
getPlace(); 
