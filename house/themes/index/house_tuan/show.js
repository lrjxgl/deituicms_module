
var App=new Vue({
	el:"#App",
	data:function(){
		return {
				userList:[]
		}
	
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_user&ajax=1&tid="+tid,
				dataType:"json",
				success:function(res){
					that.userList=res.data.list;
				}
			})
		}
	}	
})

$(document).on("click","#contact-btn",function(){
	$("#contact-modal").show();
})
$(document).on("click","#join-btn",function(){
	$("#join-modal").show();
})
$(document).on("click","#join-submit",function(){
	$.ajax({
		url:"/module.php?m=house_tuan_user&a=save&ajax=1",
		data:$("#join-form").serialize(),
		dataType:"json",
		type:"POST",
		success:function(res){
			skyToast(res.message);
			if(!res.error){
				$("#join-modal").hide();
				App.getList();
			}
			
		}
	})
})