var App=new Vue({
	el:"#App",
	data:function(){
		return {
			articleList:[],
			blogList:[],
			flList:[],
			fswList:[],
			 
			placeList:[],
			placeFirst:true,
			placePage:0,
			place_allow:"all",
			tagList:[],
			tagShow:false,
			place_tag:"",
			phList:[],
			tab:"article"
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getArticlelist();
			this.getBloglist();
			this.getFllist();
			this.getFswlist();
			this.getTag();
			this.getPlace();
			this.getPhList();
			
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
				this.articleList=p.articleList;
				this.blogList=p.blogList;
				this.flList=p.flList;
				this.fswList=p.fswList;
				this.placeList=p.placeList;
			 
				this.placeFirst=p.placeFirst;
				this.placePage=p.placePage;
				this.place_allow=p.place_allow;
				this.tagList=p.tagList;
				this.tagShow=p.tagShow;
				this.place_tag=p.place_tag;
				this.phList=p.phList;
				this.tab=p.tab;

				return true;
			}
		},
		setTab:function(tab){
			this.tab=tab;
			this.setCache()
		},
		getBloglist:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_blog&ajax=1",
				dataType:"json",
				success:function(res){
					that.blogList=res.data.list;
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=fishing_blog&a=show&id="+id;
		},
		goHome:function(userid){
			window.location="/module.php?m=fishing_home&userid="+userid
		},
		getArticlelist:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=article&a=list&catid=697&ajax=1&type=recommend",
				dataType:"json",
				success:function(res){
					that.articleList=res.data.list;
				}
			})
		},
		goArticle:function(id){
			window.location="/index.php?m=article&a=show&id="+id;
		},
		getFllist:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fenlei&a=list&catid=78&ajax=1",
				dataType:"json",
				success:function(res){
					that.flList=res.data.list;
				}
			})
		},
		goFenlei:function(id){
			window.location="/module.php?m=fenlei&a=show&id="+id;
		},
		getFswlist:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_activity&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.fswList=res.data.list;
				}
			})
		},
		goFsw:function(actid){
			window.location="/module.php?m=fsw_activity&a=show&actid="+actid;
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
		getPhList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_bang&a=people&ajax=1",
				dataType:"json",
				success:function(res){
					that.phList=res.data.list;
				}
			})
		}
	}
})