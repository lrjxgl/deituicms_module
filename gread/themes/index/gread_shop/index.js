var App=new Vue({
	el:"#App",
	data:function(){
		return {
			bookList:[],
			catid:0,
			catList:[],
			per_page:0,
			isFirst:true,
			pageLoad:true,
			keyword:""
		}
	},
	created:function(){
		this.getPage();
		this.getList();
	},
	methods:{
		setCat:function(catid){
			this.catid=catid;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		search:function(){
			this.catid=0;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gread_shop&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					 
					 
					that.catList=res.data.catlist;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gread_shop_product&ajax=1",
				data:{
					shopid:shopid,
					per_page:this.per_page,
					catid:this.catid,
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					if(that.isFirst){
						that.bookList=res.data.booklist;
						that.isFirst=false;
					}else{
						for(var i in res.data.booklist){
							that.bookList.push(res.data.booklist[i]);
						}
					}
					that.per_page=res.data.per_page;
					
				}
			})
		},
		goDetail:function(bookid,shopid){
			window.location="/module.php?m=gread_shop_product&a=show&bookid="+bookid+"&shopid="+shopid
		},
		buy:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=gread_cart&a=toggle&ajax=1",
				data:{
					bookid:item.bookid,
					shopid:item.shopid
				},
				dataType:"json",
				success:function(res){
					skyJs.toast(res.message);
					if(res.data.op=='add'){
						item.incart=1; 
					}else{
						item.incart=0; 
					}
				}
			})
			 
		}
		 
	}
})