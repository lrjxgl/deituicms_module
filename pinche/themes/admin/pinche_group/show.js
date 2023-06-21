var App=new Vue({
	el:"#App",
	data:function(){
		return {
			group:{},
			list:[],
			line:[],
			logList:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=pinche_group&a=show&ajax=1",
				dataType:"json",
				data:{
					gid:gid
				},
				success:function(res){
					that.group=res.data.group;
					that.list=res.data.list;
					that.line=res.data.line;
				}
			})
			this.getLog();
		},
		getLog:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=pinche_group_log&ajax=1",
				dataType:"json",
				data:{
					gid:gid
				},
				success:function(res){
					that.logList=res.data.list;
				}
			})
		}
		 
	}
	
})