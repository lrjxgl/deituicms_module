var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			error:0
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_dache_order&a=new&ajax=1",
				data:{
					catid:this.catid
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
				url:"/module.php?m=pinche_dache_order&a=new&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
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
		},
		grabOrder:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认接受订单吗",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=pinche_dache_order&a=grabOrder&ajax=1",
						dataType:"json",
						data:{
							orderid:item.orderid
						},
						success:function(res){
							skyJs.toast(res.message)
							var list=[];
							for(var i in that.list){
								if(that.list[i].orderid!=item.orderid){
									list.push(that.list[i]);
								}
							}
							that.list=list;
						}
					})
				}
			})
		}
	}
})