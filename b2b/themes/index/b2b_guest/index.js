new Vue({
	el:"#App",
	data:function(){
		return{
			pageLoad:false, 
			pageHide:false,
			list:{},
			isFirst:true,
			per_page:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		goDetail:function(item){
			window.location="/module.php?m=b2b_guest&a=user&shopid="+item.shopid
		},
		getPage:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=b2b_guest&ajax=1",
				success:function(res){
					that.isFirst=false;
					that.pageLoad=true;
					that.list=res.data.list;
					that.per_page=res.data.per_page;					 
				}
			})
		},
		 
		getList:function(){
			var that=this;
			if(!that.isFirst && that.per_page==0) return false;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=b2b_guest&ajax=1",
				data:{
					per_page:that.per_page,
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page=res.data.per_page;						
				}
			})
		},
	}
})