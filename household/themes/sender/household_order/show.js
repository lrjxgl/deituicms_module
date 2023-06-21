var App=new Vue({
	el:"#app",
	data:function(){
		return {
			order:{},
			logList:[],
			appendBox:false,
			appendData:{},
			append_money:0,
			append_content:"",
			hhconfig:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"sender.php?m=household_order&a=show&ajax=1&orderid="+orderid,
				dataType:"json",
				success:function(res){
					that.order=res.data.order;
					that.logList=res.data.logList;
					that.appendData=res.data.append;
					that.hhconfig=res.data.hhconfig;
				}
			})
		},
		confirm:function(order){
			var that=this;
			skyJs.confirm({
				content:"确认正在处理吗",
				success:function(){
					$.ajax({
						url:"sender.php?m=household_order&a=confirm&ajax=1&orderid="+order.orderid,
						dataType:"json",
						success:function(res){
							skyToast(res.message);
							if(!res.error){
								that.getPage();
							}
							 
						}
					})
				}
			})
			
		},
		send:function(order){
			var that=this;
			skyJs.confirm({
				content:"确认处理完了吗",
				success:function(){
					$.ajax({
						url:"sender.php?m=household_order&a=send&ajax=1&orderid="+order.orderid,
						dataType:"json",
						success:function(res){
							skyToast(res.message);
							if(!res.error){
								that.getPage();
							}
						}
					})
				}
			})
			 
		},
		appendSubmit:function(){
			var that=this;
			 
			if( parseFloat(this.append_money)<this.order.money){
				skyJs.toast("总费用小于订金无需处理");
				return false;
			}
			if(this.append_content==''){
				skyJs.toast("费用说明不能为空");
				return false;
			}
			skyJs.confirm({
				content:"确认项目全部金额"+this.append_money+"元",
				success:function(){
					$.ajax({
						url:"/sender.php?m=household_order_append&a=save&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							orderid:that.order.orderid,
							money:that.append_money,
							content:that.append_content

						},
						success:function(res){
							that.getPage();
						}
					})
				}
			})
		}
	}
})