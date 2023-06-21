var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			ip:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=collect_ip&ajax=1&type="+type,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		addIp:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=collect_ip&a=save&ajax=1",
				data:{
					ip:that.ip
				},
				method:"POST",
				dataType:"json",
				success:function(res){
					that.getPage();
					that.ip="";
				}
			})
		},
		search:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=collect_ip&ajax=1",
				data:{
					ip:that.ip
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					 
				}
			})
		}
	}
})