var App=new Vue({
	el:"#App",
	data:function(){
		return {
			book:{},
			artList:[],
			article:{},
			page:"menu",
			noteContent:"",
			noteList:[],
			plContent:"",
			cmList:[],
			dTab:"detail"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=book&a=view&ajax=1",
				dataType:"json",
				data:{
					bookid:bookid
				},
				success:function(res){
					that.book=res.data.book;
					that.artList=res.data.artlist;
				}
			})
		},
		getCmList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=book_comment&ajax=1",
				dataType:"json",
				data:{
					bookid:bookid,
					article:this.article.id
				},
				success:function(res){
					that.cmList=res.data.data;
					 
				}
			})
		},
		getNoteList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=book_note&ajax=1",
				dataType:"json",
				data:{
					bookid:bookid,
					article:this.article.id
				},
				success:function(res){
					that.noteList=res.data.list;
					 
				}
			})
		},
		getDetail:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=book_article&a=view&ajax=1",
				dataType:"json",
				data:{
					bookid:bookid,
					id:item.id
				},
				success:function(res){
					that.article=res.data.data;
					that.getCmList();
					that.getNoteList(); 
				}
			})
		},
		setDetail:function(item){
			this.getDetail(item) 
			this.page="detail";
			
		},
		goMenu:function(){
			var that=this;
			setTimeout(function(){
				that.page="menu";
			},30)
			
		},
		foldToggle:function(a){
			if(a.openFold){
				a.openFold=0;
			}else{
				a.openFold=1;
			}
		},
		saveComment:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=book_comment&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					bookid:bookid,
					article:this.article.id,
					content:this.plContent
				},
				success:function(res){
					that.getCmList()
				}
			})
		},
		saveNote:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=book_note&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					bookid:bookid,
					article:this.article.id,
					content:this.noteContent
				},
				success:function(res){
					that.getNoteList()
				}
			})
		},
		dTabSet:function(t){
			this.dTab=t;
			switch(t){
				case "comment":
					this.getCmList();
					break;
				case "note":
					this.getNoteList();
					break;
			}
		}
		
	}
	
})