var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			list:{},
			isFirst:true,
			per_page:0,
			keyword:""
		}
	},
	created:function(){
		this.keyword=keyword;
		this.getPage();
	},
	methods:{
		goProduct:function(id){
			window.location="/module.php?m=car_product&a=show&productid="+id;
		},
		 
		search:function(){
			this.getPage();
		},
		 
		getPage:function(){
			var that=this;
			if(that.keyword=='') return false;
			$.ajax({
				url:"/module.php?m=car_search&a=product&ajax=1",
				data:{
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.list=res.data.list;
					that.per_page=res.data.per_page;
				}
			})
		},
		 
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=car_search&a=product&ajax=1",
				data:{
					keyword:this.keyword,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					
					that.per_page=res.data.per_page;
				}
			})
		}
	}
});