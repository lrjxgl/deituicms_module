var App=new Vue({
	el:"#App",
	data:function(){
		return {
			proList:[],
			ppList:[],
			keyword:"",
			tab:"product",
			isFirst:true,
			per_page:0,
			pageLoad:false
		}
	},
	created:function(){
		
		if(!this.getCache()){
			this.keyword=keyword;
			this.getProduct();
		}else{
			console.log("yes")
		}
		
	},
	methods:{
		setCache:function(){
			var  cacheData=this.$data;
			cacheData.expire=Date.parse(new  Date())/1000+120;
			localStorage.setItem("page-zbtao_search",JSON.stringify(cacheData) );
		},
		getCache:function(){
			var k="page-zbtao_search";
			var res=localStorage.getItem(k);
			 
			if(res!=null){
				var d=JSON.parse(res);
				
				if(Date.parse(new  Date())/1000>d.expire){
					
					return false;
				}
				this.proList=d.proList;
				this.ppList=d.ppList;
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
				case "pp":
					this.getPP();
					break;
				default:
					this.getProduct();
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
		getProduct:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=zbtao_search&a=product&ajax=1",
				dataType:"json",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				success:function(res){
					
					if(that.isFirst){
						that.proList=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.proList.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		},
		getPP:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=zbtao_search&a=pp&ajax=1",
				dataType:"json",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				success:function(res){
					if(that.isFirst){
						that.ppList=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.ppList.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		},
		goLive:function(liveid){
			window.location="/module.php?m=zbtao_live&a=show&liveid="+liveid;
		},
		goPP:function(ppid){
			window.location="/module.php?m=zbtao_pp&a=show&ppid="+ppid;
		}
	}
})