var App=new Vue({
	el:"#App",
	data:function(){
		return {
			data:{
				description:"",
				price:0,
				imgsdata:""
			},
			description:"",
			price:1,
			imgsdata:"",
			imgList:[],
			catBox:false,
			catid:0,
			cat_label:"请选择分类",
			catList:[],
			catList2:[],
			catList3:[],
			catid1:0,
			catid2:0,
			lat:0,
			lng:0,
			baoyou:0,
			areaModel:false,
			address:"请选择地址",
			provinceList:[],
			province:{},
			cityList:[],
			city:{},
			townList:[],
			town:{},
			areaName:"区域",
			addr_upid:0,
			addrHeight:400,
			addr:{},
			cityid:0
			
		}
	},
	created:function(){
		this.getPage();
		this.getCityList(0);
	},
	methods:{
		getLbs:function(){
			//地理位置
			var that=this;
			var geolocation = new BMap.Geolocation();
			var latlng=GPS.get();
			var lat=0,lng=0;
			if(!latlng){
				
				geolocation.getCurrentPosition(function(r){
				if(this.getStatus() == BMAP_STATUS_SUCCESS){
					that.lat=r.point.lat;
					that.lng=r.point.lng;
					GPS.set({
						lat:lat,
						lng:lng
					});
					
					
				}
				else {
					alert('获取GPS失败'+this.getStatus());
					
				}        
				},{enableHighAccuracy: true})
			}else{
				that.lat=latlng.lat;
				that.lng=latlng.lng;
			}
			 
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_product&a=add&ajax=1",
				dataType:"json",
				data:{
					productid:0
				},
				success:function(res){
					that.catList=res.data.catList;
				}
			})
		},
		setImgsData:function(e){
			this.imgsdata=e;
			console.log(e)
		},
		submit:function(){
			var that=this;
			if(this.description==''){
				skyToast("请填写商品描述")
				return false;
			}
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=ershou_product&a=save&ajax=1",
				dataType:"json",
				type:"post",
				data:{
					description:this.description,
					imgsdata:this.imgsdata,
					price:this.price,
					catid:this.catid,
					lat:this.lat,
					lng:this.lng,
					baoyou:this.baoyou,
					cityid:this.cityid
				},
				success:function(res){
					skyToast(res.message)
					that.description="";
					setTimeout(function(){
						goBack(); 
					},1000)
					
				}
			})
		},
		changeCat:function(item,t){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_category&a=list&ajax=1",
				dataType:"json",
				data:{
					pid:item.catid
				},
				success:function(res){
					if(t==2){
						that.catList2=res.data.list;
						that.catid1=item.catid;
						that.catid2=0;
						that.catList3=[];
					}else if(t==3){
						that.catList3=res.data.list;
						that.catid2=item.catid;
					}
					
				}
			})
		},
		choiceCat:function(item){
			this.catid=item.catid;
			this.cat_label=item.title;
			this.catBox=false;
		},
		getCityList:function(up,level){
			if(level==undefined){
				level=0;
			}
			var that=this;
			$.ajax({
				url:"/index.php?m=district&a=list&ajax=1",
				data:{
					upid:up.id,				
				},
				dataType:"json",
				success:function(res){
					if(level==0){
						that.provinceList=res.data.list;
						that.province={};
						that.city={};
						that.town={}
						that.cityList=[];
						that.townList=[];
					}else if(level==1){
						that.cityList=res.data.list;
						
						that.province=up;
						that.city={};
						that.town={}
						that.townList=[];
					}else if(level==2){
						that.city=up;
						that.town={}
						that.townList=res.data.list;
					}
				}
			})
		},
		setAddr:function(){
			var p=c=t="";
			var pid=cid=tid=0;
			this.areaName="区域";
			if(Object.keys(this.province).length>0){
				p=this.province.name;
				pid=this.province.id;
				this.areaName=p;
				this.cityid=pid;
			}
			if(Object.keys(this.city).length>0){
				c=this.city.name;
				cid=this.city.id;
				this.areaName=c;
				this.cityid=cid;
			}
			if(Object.keys(this.town).length>0){
				t=this.town.name;
				tid=this.town.id;
				this.areaName=t;
				this.cityid=tid;
			}
			this.address=p+"·"+c+"·"+t;
			this.areaModel=false;
			 
		}
	}
})