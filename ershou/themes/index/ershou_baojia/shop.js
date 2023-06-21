var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			 
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_baojia&a=shop&ajax=1",
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
				url:"/module.php?m=ershou_baojia&a=shop&ajax=1",
				data:{
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
		pass:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认接受报价吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=ershou_baojia&a=pass&ajax=1",
						dataType:"json",
						data:{
							id:item.id
						},
						success:function(res){
							if(res.error){
								skyToast(res.message);
								return false;
							}
							 
						}
					})
				}
			})
			
		},
		forbid:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认拒绝报价吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=ershou_baojia&a=forbid&ajax=1",
						dataType:"json",
						data:{
							id:item.id
						},
						success:function(res){
							if(res.error){
								skyToast(res.message);
								return false;
							}
							 
						}
					})
				}
			})
			
		}
	}
})