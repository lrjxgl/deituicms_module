var cacheKey="taoke_search";
var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			word:"",
			orderby:"",
			catmap:""
		}
	},
	created:function(){
		this.word=keyword;
		this.catmap=catmap;
		console.log(this.word)
		if(this.word!=''){
			this.getPage();
		}else{
			this.getCache();
		}		
	},
	methods:{
		setCache:function(){
			var val={
				list:this.list,
				per_page:this.per_page,
				isFirst:this.isFirst,
				word:this.word,
				orderby:this.orderby,
				catmap:this.catmap,
				scrollTop:0
			}
			localStorage.setItem(cacheKey,JSON.stringify(val));
		},
		getCache:function(){
			var val=localStorage.getItem(cacheKey);
			if(!val) return false;
			var v=JSON.parse(val);
			this.list=v.list;
			this.per_page=v.per_page;
			this.isFirst=v.isFirst;
			this.word=v.word;
			this.orderby=v.orderby;
			this.catmap=v.catmap;
			$(window).scrollTop(v.scrollTop);
		},
		setOrder:function(order){
			this.orderby=order;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke_search&a=searchapi&ajax=1&word="+this.word,
				data:{
					catmap:this.catmap
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						
						return false;
					}
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.setCache();
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=taoke_search&a=searchapi&ajax=1&word="+this.word,
				data:{
					per_page:this.per_page,
					orderby:this.orderby,
					catmap:this.catmap
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						
						return false;
					}
					if(that.isFirst){
						that.isFirst=false;
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}		
					that.per_page=res.data.per_page;
					that.setCache();
				}
			})
		},
		goDetail:function(id){
			window.location="/module.php?m=taoke_search&a=show&id="+id;
		}
	}
})
$(function(){
	$(window).on("scroll",function(e){
		app.scrollTop=$(window).scrollTop();
	})
})