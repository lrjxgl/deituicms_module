
var app=new Vue({
	el:"#app",
	data:function(){
		return {
			list:[],
			type:type,
			isFirst:true,
			per_page:0
		}
	},
	created:function(){
		this.getPage();
		$("#app").show();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=olprint_order&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/moduleshop.php?m=olprint_order&ajax=1",
				data:{
					type:that.type,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					that.isFirst=false;
					
				}
			})
		},
		goOrder:function(orderid){
			window.location="/moduleshop.php?m=olprint_order&a=show&orderid="+orderid
		},
		setType:function(t){
			this.type=t;
			this.isFirst=true;
			this.per_page=0;
			this.getPage();
		},
		confirm:function(item){
			if(!postCheck.canPost()){
				return false;
			}
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=olprint_order&a=confirm&ajax=1&orderid="+item.orderid,
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					if(res.error){	
						return false;
					}
					item.status=1;
				}
			})
			
		},
		cancel:function(item){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			skyJs.confirm({
				content:"确认取消订单吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=olprint_order&a=cancel&ajax=1&orderid="+item.orderid,
						dataType:"json",
						success:function(res){
							skyToast(res.message);
							if(res.error){	
								return false;
							}
							var list=[];
							for(var  i in that.list){
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
});