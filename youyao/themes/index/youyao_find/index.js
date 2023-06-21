var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			lat:28,
			lng:112
		}
	},
	created:function(){
		 
		
	},
	methods:{
		goPm:function(uid){
			window.location="/index.php?m=pm&a=detail&userid="+uid
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=youyao_find&a=list&ajax=1",
				data:{
					catid:this.catid,
					lat:this.lat,
					lng:this.lng
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=youyao_find&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					lat:this.lat,
					lng:this.lng
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		}
	}
})

AMap.plugin('AMap.Geolocation', function() {
  var geolocation = new AMap.Geolocation({
	// 是否使用高精度定位，默认：true
	enableHighAccuracy: true,
	// 设置定位超时时间，默认：无穷大
	timeout: 10000,
	// 定位按钮的停靠位置的偏移量
	offset: [10, 20],
	//  定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
	zoomToAccuracy: true,     
	//  定位按钮的排放位置,  RB表示右下
	position: 'RB'
  })

  geolocation.getCurrentPosition(function(status,result){
		if(status=='complete'){
			onComplete(result)
		}else{
			onError(result)
		}
  });

  function onComplete (res) {
	  console.log(res)
	App.lat=res.position.lat
	App.lng=res.position.lng
	App.getPage();
  }

  function onError (data) {
	 App.getPage();
	 console.log(data)
  }
})