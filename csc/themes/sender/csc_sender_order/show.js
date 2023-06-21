new Vue({
	el:"#App",
	data:function(){
		return {
			order:{},
			addr:{},
			prolist:{},
			shop:{},
			data:{},
			paotui:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=csc_sender_order&a=show&ajax=1&ptorderid="+ptorderid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.order=res.data.order;
					that.addr=res.data.addr;
					that.prolist=res.data.prolist;
					that.data=res.data.data;
					if(res.data.data.tablename=='paotui'){
						that.paotui=res.data.paotui;
					}
				}
			})
		},
		cancel:function(ptorderid){
			var that=this;
			if(confirm("确认取消订单吗？")){
				$.ajax({
					dataType:"json",
					url:"/sender.php?m=csc_sender_order&a=cancel&ajax=1&ptorderid="+ptorderid,
					success:function(res){
						skyToast(res.message)
						if(res.error){
							return false;
						}
						that.data.status=4;
						that.data.status_name="已取消";
					}
				})
			}
		},
		confirm:function(ptorderid){
			var that=this;
			if(confirm("确认接单吗?")){
				$.ajax({
					dataType:"json",
					url:"/sender.php?m=csc_sender_order&a=confirm&ajax=1&ptorderid="+ptorderid,
					success:function(res){
						skyToast(res.message)
						if(res.error){
							return false;
						}

						that.data.status=1;
						that.data.status_name="配送中";
					}
				})
			} 
					
			 
		},
		send:function(ptorderid){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/sender.php?m=csc_sender_order&a=send&ajax=1&ptorderid="+ptorderid,
				success:function(res){
					skyToast(res.message)
					if(res.error){
						
						return false;
					}
					that.data.status=2;
					that.data.status_name="待收货";
				}
			})
		}
	}
})