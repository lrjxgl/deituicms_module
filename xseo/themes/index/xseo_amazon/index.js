var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			keyword:"iphone",
			wordList:[],
			showWordModal:false,
			wordRow:{}
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		searchWord:function(w){
			this.keyword=w;
			this.search();
		},
		search:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		searchAsin:function(asin){
			var that=this;

			$.ajax({
				url:"/module.php?m=xseo_amazon&a=search&ajax=1",
				data:{
					asin:asin
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					
					that.pageLoad=true;
				}
			})
			
		},
		showWord:function(item,type){
			
			var that=this;
			$.ajax({
				url:"/module.php?m=xseo_amazon&a=getword&ajax=1",
				data:{
					asin:item.asin, 
					type:type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.wordList=res.data.list;
					if(that.wordList.length>0){
						that.showWordModal=true; 
					}
					
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=xseo_amazon&a=search&ajax=1",
				data:{
					catid:this.catid,
					keyword:this.keyword
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
					that.wordRow=res.data.wordRow;
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
				url:"/module.php?m=xseo_amazon&a=search&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					keyword:this.keyword
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
					
					that.wordRow=res.data.wordRow;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		}
	}
})