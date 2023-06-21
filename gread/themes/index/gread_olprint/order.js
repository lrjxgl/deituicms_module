var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			type:''
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=olprint_order&a=my&ajax=1",
				data:{
					type:this.type
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
				url:"/module.php?m=olprint_order&a=my&ajax=1",
				data:{
					type:this.type,
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
		cancel:function(item){
			skyJs.confirm({
				content:"确认取消打印订单吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=olprint_order&a=cancel&ajax=1",
						data:{
							orderid:item.orderid
						},
						dataType:"json",
						success:function(res){
							if(res.error){
								skyToast(res.message);
								return false;
							}
							item.status=4;
						}
					})
				}
			})
		},
		finish:function(item){
			skyJs.confirm({
				content:"确认打印完成吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=olprint_order&a=finish&ajax=1",
						data:{
							orderid:item.orderid
						},
						dataType:"json",
						success:function(res){
							if(res.error){
								skyToast(res.message);
								return false;
							}
							item.status=3;
						}
					})
				}
			})
		}
	}
})
