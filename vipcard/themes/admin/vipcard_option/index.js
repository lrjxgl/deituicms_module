var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			pageData:{},
			paymoney:0,
			money:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=vipcard_option&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		},
		add:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=vipcard_option&a=save&ajax=1",
				dataType:"json",
				data:{
					money:that.money,
					paymoney:that.paymoney
				},
				method:"post",
				success:function(res){
					if(!res.error){
						that.getPage();
					}
				}
			})
		},
		del:function(id){
			var that=this;
			if(confirm("删除后不可恢复，确认删除吗？")){
				$.ajax({
					url:"/moduleadmin.php?m=vipcard_option&a=delete&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						that.getPage();
					}
				})
			}
		}
	}
})