var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			keyword:""
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		goGuest:function(item){
			window.location="/module.php?m=youyao_guest&a=user&shopid="+item.shopid+"&productid="+item.productid
		},
		goShop:function(shopid){
			window.location="/module.php?m=youyao_shop&a=show&shopid="+shopid
		},
		search:function(){
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=youyao_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=youyao_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		}
	}
})