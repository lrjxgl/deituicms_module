var App=new Vue({
	el:"#App",
	data:function(){
		return {
			bookid:0,
			book:{},
			list:[],
			article:{},
			yhShow:false,
			prompt:""
		}
	},
	created:function(){
		this.bookid=bookid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_book&a=edit_article&ajax=1",
				dataType:"json",
				data:{
					bookid:that.bookid
				},
				success:function(res){
					console.log(res)
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.book=res.data.book;
					that.list=res.data.list;
					that.article=res.data.article;
				}
			})
			
		},
		getArticle:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_book_article&a=add&ajax=1",
				dataType:"json",
				data:{
					id:item.id
				},
				success:function(res){
				 
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.article=res.data.data;
					
				}
			})
		},
		submit:function(){
			var that=this;
			that.article.content=$("#content").html();
			$.ajax({
				url:"/module.php?m=aichat_book_article&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:that.article,
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					 
				}
			})
			
		},
		newAnswer:function(){
			var that=this;
			skyJs.confirm({
				content:"确认重新生成吗",
				success:function(){
					var that=this;
					$.ajax({
						url:"/module.php?m=aichat_book_article&a=reanswer&ajax=1",
						dataType:"json",
						data:{
							id:that.article.id,
							prompt:"重新写一篇"
						},
						type:"POST",
						success:function(res){
							skyToast(res.message);
							if(res.error){
								
								return false;
							}
							 
							
						}
					})
				}
			})
		},
		reAnswer:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_book_article&a=reanswer&ajax=1",
				dataType:"json",
				data:{
					id:this.article.id,
					prompt:this.prompt
				},
				type:"POST",
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					 
					
				}
			})
		}
	}
})