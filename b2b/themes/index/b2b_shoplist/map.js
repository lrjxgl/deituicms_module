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
function getshop(){
	$.ajax({
		url:"/module.php?m=b2b_shoplist&ajax=1",
		dataType:"json",
		success:function(res){
			for(var i in res.data.shopList){
				var mp=res.data.shopList[i];
				markers[i]= new BMapGL.Marker(new BMapGL.Point(mp.lng, mp.lat));
				map.addOverlay(markers[i]); 
				clickInfo(i,mp,point)
			}
		}
	})
	console.log(markers);
}
function clickInfo(i,shop,point){
	 
	var sContent=`
		<div style="width:300px;height:140px;padding:10px;z-index:999;position:relative">
			<div class="cl-red mgb-5">`+shop.title+`</div>
			<div class="mgb-5 cl2">`+shop.description+`</div>
			 
			<div class="none">
				<div class="flex-1"></div>
				<a class="cl-primary mgr-10" href="/module.php?m=b2b_shop&shopid=`+shop.shopid+`">去看看</a>
			</div>
			
		</div>
	`;
	var opts = {
		width: 350,
		height: 140,
		title: '商家详情'
	};
	var infoWindow = new BMapGL.InfoWindow(sContent,opts);
	infoWindow.disableCloseOnClick()
	infoWindow.addEventListener("close",function(){
		$("#goshop").hide();
	})
	markers[i].addEventListener('click', function(){
	    this.openInfoWindow(infoWindow,point);
		console.log(shop)
		$("#goshop").show().attr("href","/module.php?m=b2b_shop&shopid="+shop.shopid);
	});
	
}
getshop(); 
