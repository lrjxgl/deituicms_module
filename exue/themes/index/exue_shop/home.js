var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			shop:{},
			shopid:shopid,
			tab:"blog",
			isFirst:true,
			per_page:0,
			blogList:[],
			courseList:[],
			teacherList:[],
			pageCacheKey:"page-exue-shop-home"
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getShop();
			this.getList();	
		}
		
	},
	methods:{
		setCache: function() {
			var val=this.$data;
			val.expire= Date.parse(new Date()) / 1000 + 300;
			localStorage.setItem(this.pageCacheKey, JSON.stringify(val));
		},
		getCache: function() {
			var that=this;
			var val =localStorage.getItem(this.pageCacheKey);
			 
			 
			if (!val) return false;
			var obj1 = JSON.parse(val);
			var time = Date.parse(new Date()) / 1000;
			if (obj1.expire < time) {
				return false;
			}
		 
			if(obj1.shopid!=this.shopid){
				return false;
			}
							
			var obj2=this.$data;
			Object.keys(obj1).forEach((key) => {
				that[key]=obj1[key] ;
			 });	 
			return true;
		},
		getShop:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=exue_shop&a=home&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.shop=res.data.shop;
					that.pageLoad=true;
					that.setCache()
				}
			})
			
		},
		setTab:function(tab){
			this.tab=tab;
			this.isFirst=true;
			this.per_page=0;
			this.setCache()
			this.getList();
		},
		getList:function(){
			switch(this.tab){
				case "blog":
					this.getBlog()
					break;
				case "course":
					this.getCourse();
					break;
				case "teacher":
					this.getTeacher();
					break;
			}
			
		},
		getBlog:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_shop&a=blog&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.blogList=res.data.list;
					that.setCache()
					 
				}
			})
		},
		getCourse:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_shop&a=course&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.courseList=res.data.list;
					that.setCache()
					 
				}
			})
		},
		getTeacher:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_shop&a=teacher&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.teacherList=res.data.list;
					that.setCache()
					 
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=exue_blog&a=show&id="+id
		}
	}
})