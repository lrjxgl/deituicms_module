var App=new Vue({
	el:"#App",
	data:function(){
		return {
			lpList:[],
			resList:[],
			keyword:"",
			tab:"loupan",
			isFirst:true,
			per_page:0,
			pageLoad:false
		}
	},
	created:function(){
		
		if(!this.getCache()){
			this.keyword=keyword;
			this.getLoupan();
		}else{
			console.log("yes")
		}
		
	},
	methods:{
		setCache:function(){
			var  cacheData=this.$data;
			cacheData.expire=Date.parse(new  Date())/1000+120;
			localStorage.setItem("page-house_search",JSON.stringify(cacheData) );
		},
		getCache:function(){
			var k="page-house_search";
			var res=localStorage.getItem(k);
			 
			if(res!=null){
				var d=JSON.parse(res);
				
				if(Date.parse(new  Date())/1000>d.expire){
					
					return false;
				}
				this.lpList=d.lpList;
				this.resList=d.resList;
				this.isFirst=d.isFirst;
				this.pageLoad=d.pageLoad;
				this.keyword=d.keyword;
				this.tab=d.tab; 
				this.per_page=d.per_page;
				 
				
				return true;
			}
			return false;
		},
		getPage:function(){
			switch(this.tab){
				case "loupan":
					this.getLoupan();
					break;
				default:
					this.getResource();
					break;
			}
		},
		search:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getPage();
		},
		setTab:function(t){
			this.tab=t;
			this.isFirst=true;
			this.per_page=0;
			this.getPage();
		},
		getLoupan:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=house_search&a=loupan&ajax=1",
				dataType:"json",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				success:function(res){
					
					if(that.isFirst){
						that.lpList=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.lpList.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		},
		getResource:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=house_search&a=resource&ajax=1",
				dataType:"json",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				success:function(res){
					if(that.isFirst){
						that.resList=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.resList.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		},
		goLoupan:function(id){
			window.location="/module.php?m=house_loupan&a=show&id="+id;
		},
		goResource:function(id){
			window.location="/module.php?m=house_resource&a=show&id="+id;
		}
	}
})