var App=new Vue({
	el:"#app",
	data:function(){
		return {
			order:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"sender.php?m=paotui_order&a=show&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					that.order=res.data.order;
				}
			})
		},
		confirm:function(order){
			if(confirm("确认办完了吗")){
				var that=this;
				$.ajax({
					url:"sender.php?m=paotui_order&a=confirm&ajax=1&id="+order.id,
					dataType:"json",
					success:function(res){
						skyToast(res.message);
						if(!res.error){
							window.location.reload();
						}
						 
					}
				})
			}
		},
		send:function(order){
			if(confirm("确认办完了吗")){
				var that=this;
				$.ajax({
					url:"sender.php?m=paotui_order&a=send&ajax=1&id="+order.id,
					dataType:"json",
					success:function(res){
						skyToast(res.message);
						if(!res.error){
							window.location.reload();
						}
					}
				})
			}
		}
	}
})