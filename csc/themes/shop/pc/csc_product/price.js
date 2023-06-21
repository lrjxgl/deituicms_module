var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:{},
			per_page:0,
			isFirst:true,
			supList:[],
			keyword:"",
			supid:0,
			catid:0,
			catList:[],
			isrecommend:0,
			ishot:0,
			bstatus:0,
			isplan:0,
			isfixed:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		updatePrice:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_product&a=priceSave&ajax=1",
				dataType:"json",
				data:{
					id:item.id,
					total_num:item.total_num
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
					}else{
						//item.price=item.newPrice;
					}
					
				}
			})
			
		},
		search:function(){
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_product&orderby=total_num&ajax=1",
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
					that.supList=res.data.supList;
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
				url:"/moduleshop.php?m=csc_product&orderby=total_num&ajax=1",
				data:{
					per_page:this.per_page,
					title:this.keyword,
					supid:this.supid,
					isrecommend:this.isrecommend,
					ishot:this.ishot,
					catid:this.catid,
					bstatus:this.bstatus,
					isfixed:this.isfixed,
					isplan:this.isplan
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
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
		}
	}
})