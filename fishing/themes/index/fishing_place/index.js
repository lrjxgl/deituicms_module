var app=new Vue({
	el:"#App",
	data:function(){
		return {
			tab:"recommend",
			placeList:[],
			placeFirst:true,
			placePage:0,
			place_allow:"all",
			tagList:[],
			tagShow:false,
			place_tag:"",
			lat:0,
			lng:0,
			wHeight:400
		}
	},
	created:function(){
		this.wHeight=window.innerHeight-100;
		 console.log(this.wHeight)
		this.getPlace();
		this.getTag(); 
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		 
		getPlace:function(){
			var that=this;
			var type="recommend";
			if(that.tab=='near'){
				type="near";
			}
			if(that.placePage==0 && !that.placeFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_place&ajax=1",
				dataType:"JSON",
				data:{
					tag:this.place_tag,
					allow:this.place_allow,
					per_page:that.placePage,
					type:type,
					lat:this.lat,
					lng:this.lng
				},
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.placePage=res.data.per_page;
					if(that.placeFirst){
						that.placeList=res.data.list;
						that.placeFirst=false;
					}else{
						for(var i in res.data.list){
							that.placeList.push(res.data.list[i])
						}
					}
					
				}
			})
		},
		goPlace:function(placeid){
			window.location="/module.php?m=fishing_place&a=show&placeid="+placeid;
		},
		setAllow:function(a){
			this.place_allow=a;
			this.placeFirst=true;
			this.placePage=0;
			this.getPlace();
		},
		getTag:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_tag&ajax=1",
				dataType:"JSON",
				
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.tagList=res.data.list;
				}
			})
		},
		tagToggle:function(){
			if(this.tagShow){
				this.tagShow=false;
			}else{
				this.tagShow=true;
			}
		},
		setPlaceTag:function(t){
			this.place_tag=t;
			this.tagShow=false;
			this.placeFirst=true;
			this.placePage=0;
			this.getPlace();
		},
	}
})