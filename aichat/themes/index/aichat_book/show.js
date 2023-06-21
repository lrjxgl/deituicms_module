var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			bookid:0,
			book:{},
			showArticle:false,
			article:{}
		}
	},
	created:function(){
		this.bookid=bookid;
		this.getPage();
	},
	methods:{
		goArticle:function(item){
			this.article=item;
			this.showArticle=true;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_book&a=show&ajax=1",
				data:{
					bookid:this.bookid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.book=res.data.book;
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
				url:"/module.php?m=aichat_book&a=show&ajax=1",
				data:{
					bookid:this.bookid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.book=res.data.book;
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