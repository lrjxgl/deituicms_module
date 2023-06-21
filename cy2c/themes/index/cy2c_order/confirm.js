var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			pageData:{}
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
				url:"/module.php?m=cy2c_order&a=confirm&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		changeAddr:function(user_address_id){
			var that=this;
			$.ajax({
				url:"/module.php?m=cy2c_order&a=confirm&ajax=1",
				data:{
					user_address_id:user_address_id
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		},
		changePaytype:function(paytype){
			this.pageData.paytype=paytype;
		}
	}
});