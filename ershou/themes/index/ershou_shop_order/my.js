var isFirst=true;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			tab:"",
			per_page:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_shop_order&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					isFirst=false;
				}
			})
		},
		setType:function(t){
			this.tab=t;
			this.per_page=0;
			isFirst=true;
			this.getList();
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !isFirst){
				skyToast("加载完毕");
				return false;
			}
			$.ajax({
				url:"/module.php?m=ershou_shop_order&a=my&ajax=1",
				data:{
					per_page:that.per_page,
					type:this.tab
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;			 
					that.per_page=res.data.per_page;
				}
			})
		},
		goProduct:function(fsid){
			window.location="/module.php?m=ershou&a=show&fsid="+fsid;
		},
		goDetail:function(orderid){
			window.location="/module.php?m=ershou_shop_order&a=show&orderid="+orderid;
		},
		loadMore:function(){
			this.getList();
		},
		goPay:function(orderid){
			window.location="/module.php?m=ershou_shop_order&a=pay&orderid="+orderid;
		}
	}
})