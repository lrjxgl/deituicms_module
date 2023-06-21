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
			placeList:[],
			placeFirst:true,
			placePage:0,
			place_allow:"all",
			tagList:[],
			tagShow:false,
			place_tag:"",
			fsList:[]
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getBlog();
			this.getPlace();
			this.getCheckin();
			this.getTag();
			this.getPeople()
		}
		 
		
	},
	methods:{
		setCache:function(){
			sessionStorage.setItem("fishing-index",JSON.stringify(this.$data));
		},
		getCache:function(){
			var v=sessionStorage.getItem("fishing-index");
			if(v==undefined){
				return false;
			}else{
				var p=JSON.parse(v);
				console.log(p)
				this.tab=p.tab;
				this.ckList=p.ckList;
				this.blogList=p.blogList;
				this.placeList=p.placeList;
				this.place_allow=p.place_allow;
				this.tagList=p.tagList;
				this.tagShow=p.tagShow;
				this.place_tag=p.place_tag;
				this.fsList=p.fsList
				return true;
			}
		},
		setTab:function(t){
			this.tab=t;
			this.setCache()
		},
		getCheckin:function(){
			var that=this;
			if(this.ckPage==0 && !this.ckFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_checkin&ajax=1",
				dataType:"JSON",
				data:{
					per_page:this.ckPage
				},
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
		getPlace:function(){
			var that=this;
			if(that.placePage==0 && !that.placeFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_place&ajax=1",
				dataType:"JSON",
				data:{
					tag:this.place_tag,
					allow:this.place_allow,
					per_page:that.placePage
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
		getBlog:function(){
			var that=this;
			if(this.blogPage==0 && !this.blogFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_blog&ajax=1",
				dataType:"JSON",
				data:{
					per_page:this.blogPage
				},
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
			window.location="/module.php?m=fishing_blog&a=show&id="+id;
		},
		goHome:function(userid){
			window.location="/module.php?m=fishing_home&userid="+userid
		},
		getPeople:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing&a=people&ajax=1",
				dataType:"JSON",
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.fsList=res.data.fsList;
				}
			})
		}
	}
})

$(document).on("scroll",function(){
	var y1=$("#navTabOffset").offset().top-50;
	var y2=$(window).scrollTop();
	if(y2>y1){
		$("#navTab").addClass("navTabFixed");
	}else{
		$("#navTab").removeClass("navTabFixed");
	}
	console.log(y2,y1);
})