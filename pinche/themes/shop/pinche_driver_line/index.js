var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			keyword:"",
			scList:[],
			page:"all" 
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		search:function(){
			var that=this;
			this.page='search';
			$.ajax({
				url:"/moduleshop.php?m=pinche_driver_line&a=search&ajax=1",
				 
				dataType:"json",
				data:{
					keyword:this.keyword
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.scList=res.data.list;
					 
				}
			})
		},
		choice:function(lineid){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_driver_line&a=save&ajax=1",
				 
				dataType:"json",
				data:{
					lineid:lineid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.page="all"; 
					 
				}
			})
		},
		del:function(id){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_driver_line&a=delete&ajax=1",
				 
				dataType:"json",
				data:{
					id:id
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.getPage(); 
					 
				}
			})
		},
		toggleStatus:function(id){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_driver_line&a=status&ajax=1",
				 
				dataType:"json",
				data:{
					id:id
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.getPage(); 
					 
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=pinche_driver_line&ajax=1",
				 
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
				url:"/moduleshop.php?m=pinche_driver_line&ajax=1",
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
		}
	}
})
