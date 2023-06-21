var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			page:"index",
			keyword:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		
		search:function(){
			this.getPage();
		},
		setPage:function(page){
			this.page=page;
			this.pageLoad=false;
			this.pageData={};
			this.getPage();
		},
		getIndex:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=mmjz_cbd_shop&a=list&ajax=1&cbdid="+cbdid,
				data:{
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		getPage:function(){
			if(this.page=='index'){
				this.getIndex();
			}else{
				this.getShop();
			}
		},
		getShop:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=mmjz_cbd_shop&a=shop&ajax=1&cbdid="+cbdid,
				data:{
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		toggleAdd:function(shopid){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=mmjz_cbd_shop&a=toggleadd&ajax=1&cbdid="+cbdid+"&shopid="+shopid,
				dataType:"json",
				success:function(res){
					var list=that.pageData.list;
					for(var i=0;i<list.length;i++){
						if(list[i].shopid==shopid){
							list[i].status=res.data;
						}
					}
					that.pageData.list=list;
				}
			})
		},
		del:function(shopid){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=mmjz_cbd_shop&a=toggleadd&ajax=1&cbdid="+cbdid+"&shopid="+shopid,
				
				dataType:"json",
				success:function(res){
					var list=that.pageData.shoplist;
					var newlist={};
					for(var i=0;i<list.length;i++){
						if(list[i].shopid!=shopid){
							newlist[i]=list[i];
						}
					}
					that.pageData.shoplist=newlist;
				}
			})
		}
	}
})