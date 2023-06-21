var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			pageTab:"article",
			catList:[]
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getPage();
		} 
		
	},
	methods:{
		getCache:function(){
			var k="page-m-fenlei-index";
			var res=localStorage.getItem(k);
			 
			if(res!=null){
				var d=JSON.parse(res);
			 
				if(Date.parse(new  Date())/1000>d.expire){
					return false;
				}
				 
				this.list=d.list;
				this.isFirst=d.isFirst;
				this.pageLoad=d.pageLoad;
				this.catList=d.catList;
				this.catid=d.catid;
				this.per_page=d.per_page;
				this.pageTab=d.pageTab;
				
				return true;
			}
			return false;
		},
		setCache:function(){
			var k="page-m-fenlei-index";
			var data=this.$data;
			data.expire=Date.parse(new  Date())/1000+120;
			localStorage.setItem(k,JSON.stringify(data));
		},
		setPage:function(t){
			this.pageTab=t;
			$(window).scrollTop($("#pageNavDot").offset().top-45);
			this.setCache();
		},
		setCat:function(catid){
			this.pageTab="cat";
			this.catid=catid;
			this.isFirst=true;
			this.per_page=0;
			$(window).scrollTop($("#pageNavDot").offset().top-45);
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fenlei_category&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.catList=res.data.catList;
					 
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
				url:"/module.php?m=fenlei&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
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
					that.setCache();
				}
			})
		},
		goDetail:function(id){
			window.location="/module.php?m=fenlei&a=show&id="+id
		}
	}
})
