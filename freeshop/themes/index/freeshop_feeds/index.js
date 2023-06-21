var App=new Vue({
	el:"#app",
	data:function(){
		return {
			per_page:0,
			isFirst:true,
			pageLoad:false,
			type:"",
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.getPage();
		},
		goBlog:function(id){
			window.location="/module.php?m=freeshop_product&a=show&productid="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=freeshop_feeds&ajax=1",
				data:{
					type:that.type
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
				url:"/module.php?m=freeshop_feeds&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					 
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
				}
			})
		}
	}
})