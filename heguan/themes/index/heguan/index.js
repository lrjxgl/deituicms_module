var App=new Vue({
	el:"#App",
	data:function(){
		return {
			tab:"guest",
			heList:[],
			articleList:[],
			guestList:[],
			guest:{}
		}
	},
	created:function(){
		this.getHeList();
		this.getArticleList();
		this.getGuestList();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		getHeList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=heguan_he&ajax=1",
				dataType:"json",
				success:function(res){
					that.heList=res.data.list;
				}
			})
		},
		goHe:function(item){
			window.location="/module.php?m=heguan_he&a=show&heid="+item.heid
		},
		getArticleList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=heguan_article&ajax=1",
				dataType:"json",
				success:function(res){
					that.articleList=res.data.list;
				}
			})
		},
		goArticle:function(item){
			window.location="/module.php?m=heguan_article&a=show&id="+item.id
		},
		getGuestList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=heguan_guest&ajax=1",
				dataType:"json",
				success:function(res){
					that.guestList=res.data.list;
				}
			})
		},
		 
	}
})