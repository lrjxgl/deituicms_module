var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			keyword:"",
			tab:"jianzhi"
		}
	},
	created:function(){
		
		this.keyword=keyword;
		this.getPage();
	},
	methods:{
		gourl:function(url){
			 
			window.location=url;
		},
		search:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getList();
			 
		},
		setTab:function(tab){
			this.tab=tab;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			 
			$.ajax({
				url:"/module.php?m=job_search&ajax=1",
				data:{
					keyword:this.keyword,
					tab:this.tab
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
				url:"/module.php?m=job_search&ajax=1",
				data:{
					keyword:this.keyword,
					tab:this.tab
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
