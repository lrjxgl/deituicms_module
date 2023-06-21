var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			isFirst:true,
			per_page:0,
			showChangeBox:false,
			num:0,
			productid:0,
			keyword:"",
			catList:[],
			catid:0,
			recType:0,
			statusType:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		search:function(){
			this.catid=0;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		setCat:function(){
			this.keyword="";
		
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		setRec:function(){
			this.keyword="";
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		setStatus:function(){
			this.keyword="";
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			 
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_shop_product&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					keyword:this.keyword
				},
				success:function(res){
					that.catList=res.data.catList;
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					
				}
			})
		},
		getList:function(){
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_shop_product&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					keyword:this.keyword,
					catid:this.catid,
					recType:this.recType,
					statusType:this.statusType
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					
				}
			})
		},
		changeNum_show:function(item){
			this.showChangeBox=true;
			this.productid=item.id;
			this.num=item.total_num;
		},
		changeNumSave:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_shop_product&a=changenumsave&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					productid:this.productid,
					total_num:this.num
				},
				success:function(res){
					skyJs.toast(res.message); 
					if(res.error){
						return false;
					}
					for(var i in that.list){
						if(that.list[i].id==that.productid){
							var item=that.list[i];
							item.free_num=res.data.free_num;
							item.total_num=res.data.total_num;
							that.list[i]=item;
							that.showChangeBox=false;
						}
					}
				}
			})
		},
		toggleStatus:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_shop_product&a=status&ajax=1",
				dataType:"json",
				data:{
					productid:item.id
				},
				success:function(res){
					item.status=res.data;
				}
			})
		},
		toggleRecommend:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_shop_product&a=recommend&ajax=1",
				dataType:"json",
				data:{
					productid:item.id
				},
				success:function(res){
					item.isrecommend=res.data;
				}
			})
		}
	}
	
})