var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			liveid:0
		}
	},
	created:function(){
		this.liveid=liveid; 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_live_product&a=admin&ajax=1",
				data:{
					liveid:this.liveid
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
				url:"/module.php?m=zbtao_live_product&a=admin&ajax=1",
				data:{
					liveid:this.liveid,
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
		goLive:function(liveid){
			window.location="/module.php?m=zbtao_live&a=show&liveid="+liveid;
		},
		toggleStatus:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_live_product&a=status&ajax=1",
				dataType:"json",
				data:{
					productid:item.productid
				},
				success:function(res){
					item.status=res.data.status;
					item.status_name=res.data.status_name;
				}
			})
		}
	}
})
