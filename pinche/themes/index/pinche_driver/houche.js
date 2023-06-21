var that;
var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			userList:[],
			line:{},
			driver:{},
			group:{},
			msgContent:"",
			msgList:[],
			localUser:{},
			uMapClass:""
		}
	},
	created:function(){
		that=this;
		this.getPage()
	},
	methods:{
		getPage:function(){
			$.ajax({
				url:"/module.php?m=pinche_driver&a=houche&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.line=res.data.line;
					that.driver=res.data.driver;
					that.group=res.data.group;
					that.userList=res.data.userList;
					that.getMsgList();
					setInterval(function(){
						that.getMsgList();
					},10000)
				}
			})
		},
		getMsgList:function(){
			$.ajax({
				url:"/module.php?m=pinche_group_msg&gid="+that.group.gid+"&ajax=1",
				dataType:"json",
				success:function(res){
					that.msgList=res.data.list;
					setTimeout(function(){
						$(window).scrollTop($("body").height());
					},100)
					
				}
			})
		},
		sendMsg:function(){
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=pinche_group_msg&a=save&ajax=1",
				dataType:"json",
				data:{
					gid:that.group.gid,
					content:that.msgContent
				},
				type:"POST",
				success:function(res){
					if(res.error){
						skyToast(res.message);
					}else{
						that.msgContent="";
						that.getMsgList();
					}
					
				}
			})
		},
		setLocalUser:function(u){
			var that=this; 
			this.localUser=u;
			this.uMapClass="flex-col";
			initMap();
			var driving = new BMap.DrivingRoute(map, { 
			    renderOptions: { 
			        map: map, 
			        autoViewport: true 
			} 
			});
			geolocation.getCurrentPosition(function(r){
				if(this.getStatus() == BMAP_STATUS_SUCCESS){
					geoLat=r.point.lat;
					geoLng=r.point.lng;
					
					var start = new BMap.Point(geoLng, geoLat);
					var end = new BMap.Point(u.start_lng, u.start_lat);
					map.centerAndZoom(start,18);
					driving.search(start, end);
				}
				else {
					alert('failed'+this.getStatus());
				}        
			},{enableHighAccuracy: true})
			
		}
	}
})