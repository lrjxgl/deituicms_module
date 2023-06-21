var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:{},
			per_page:0,
			isFirst:true,
			keyword:"",
			catid:0,
			catList:[],
			isrecommend:0,
			ishot:0,
			bstatus:0,
			rscount:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		search:function(){
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=b2b_product&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
					that.isFirst=false;
					that.rscount=res.data.rscount;
					that.catList=res.data.catList;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/moduleshop.php?m=b2b_product&ajax=1",
				data:{
					per_page:this.per_page,
					title:this.keyword,
					isrecommend:this.isrecommend,
					ishot:this.ishot,
					catid:this.catid,
					bstatus:this.bstatus,
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.rscount=res.data.rscount;
					if(that.isFirst){
						that.isFirst=false;
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					
					that.per_page=res.data.per_page;
					
				}
			})
		},
		statusToggle:function(item){
			$.ajax({
				url:"/moduleshop.php?m=b2b_product&a=status&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					item.status=res.data;
				}
			})
		},
		recommendToggle:function(item){
			$.ajax({
				url:"/moduleshop.php?m=b2b_product&a=recommend&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					item.isrecommend=res.data;
				}
			})
		},
		hotToggle:function(item){
			$.ajax({
				url:"/moduleshop.php?m=b2b_product&a=hot&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					item.ishot=res.data;
				}
			})
		}
	}
})