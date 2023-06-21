var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false
		}
	},
	created:function(){
		 
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=cy2c_plan&a=choice&ajax=1",
				data:{
					num:$("#num").val()
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		choice:function(placeid,title){
			this.pageLoad=false;
			$("#placeid").val(placeid);
			$("#place-title").html(title);
		},
		hideModal:function(){
			this.pageLoad=false;
		}
	}
})