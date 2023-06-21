var tablename="mod_olprint_book";
var Vm=new Vue({
				el:"#app",
				data:function(){
					return {
						pageLoad:false,
						list:[],
						per_page:0,
						isFirst:true
					}
				},
				created:function(){
					this.getList();
				},
				methods:{
					getList:function(){
						var that=this;
						$.ajax({
							url:"/module.php?m=olprint_book_fav&a=mylist&tablename="+tablename+"&ajax=1",
							dataType:"json",
							success:function(res){
								that.pageLoad=true;
								that.list=res.data.list;
								that.per_page=res.data.per_page;
								that.isFirst=false;
							}
						})
						 
					},
					loadMore:function(){
						var that=this;
						if(!that.isFirst && that.per_page==0) return false;
						$.ajax({
							url:"/module.php?m=olprint_book_fav&a=mylist&tablename="+tablename+"&ajax=1",
							data:{
								per_page:that.per_page
							},
							dataType:"json",
							success:function(res){
								that.per_page=res.data.per_page;
								 
							  
								for(var i in res.data.list){
									that.list.push(res.data.list[i]);
								}
							 
							}
						})
					 
					},
					del:function(id){
						var that=this; 
						var id=id;
						$.ajax({
							url:"/module.php?m=olprint_book_fav&a=delete&tablename="+tablename+"&ajax=1&id="+id,
							dataType:"json",
							success:function(res){
								var list=that.pageData.list;
								var nlist=[];
								for(var i=0;i<list.length;i++){
									if(list[i].id!=id){
										nlist.push(list[i]);
									}
								}
								console.log(list.length);
								var pageData=that.pageData;
								pageData.list=nlist;
								that.pageData=pageData;
							}
						})
						
					}
				}
			});