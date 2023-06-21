$(function(){
	$(document).on("click","#actJoin",function(){
		$("#actBox").show();
	})
	
	$(document).on("click","#actSubmit",function(){
		if(!postCheck.canPost()){
			return false;
		}
		$.ajax({
			url:"/module.php?m=gread_party_join&a=save&ajax=1",
			type:"POST",
			dataType:"json",
			data:$("#actForm").serialize(),
			success:function(res){
				skyJs.toast(res.message);
				if(!res.error){
					$("#actBox").hide();
				}
			}
		})
	})
})
var App=new Vue({
	el:"#jApp",
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gread_party_join&ajax=1",
				dataType:"json",
				data:{
					pid:pid
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.list=res.data.list;
				}
			})
		}
	}
})