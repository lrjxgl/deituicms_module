 
 
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			page:"blog",
			type:"",
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getPage();
		}
	},
	methods:{
		getCache:function(){
			var k="page-m-sblog";
			var res=localStorage.getItem(k);
			 
			if(res!=null){
				var d=JSON.parse(res);
				if(Date.parse(new  Date())/1000>d.expire){
					return false;
				}
				
				this.pageData=d.pageData;
				this.page=d.page;
				this.pageLoad=d.pageLoad;
				this.type=d.type;
				this.per_page=d.per_page;
				this.isFirst=d.isFirst; 
				
				return true;
			}
			return false;
		},
		setCache:function(){
			var k="page-m-sblog";
			var v=this.$data;
			v.expire=Date.parse(new  Date())/1000+120;
			localStorage.setItem(k,JSON.stringify(v));
		},
		setType:function(type){
			this.type=type;
			
			
			if(type=='topic'){
				this.page="topic";
				this.setCache()
			}else{
				this.page="blog";
				
				this.getPage();
			}
			
		},
		goBlog:function(id){
			window.location="/module.php?m=sblog_blog&a=show&id="+id;
		},
		goPeople:function(userid){
			window.location="/module.php?m=sblog_home&userid="+userid;
		},
		goTopic:function(title){
			window.location="/module.php?m=sblog_blog&a=topic&title="+encodeURI(title);
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sblog_blog&a=list&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.setCache()
				}
			})
		},
		getList:function(){
			var that=this;
			if(!that.isFirst && that.per_page==0) return false;
			$.ajax({
				url:"/module.php?m=sblog_blog&a=list&ajax=1",
				data:{
					type:that.type,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					
					that.per_page=res.data.per_page;
					
					if(that.isFirst){
						that.isFirst=false;
						that.pageData.list=res.data.list;
					}else{
						var pageData=that.pageData;
						var list=pageData.list;
						for(var i in res.data.list){
							list.push(res.data.list[i]);
						}
						that.pageData.list=list;
					}
					that.setCache()
				}
			})
		}
	}
})