var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			typelist:{},
			typeid:0,
			rscount:0,
			status:-1
		}
	},
	created:function(){
		this.getPage();
	},
	watch:{
		status:function(n,o){
			this.getList();
		}
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=paotui&ajax=1",
				data:{
					typeid:this.typeid,
					status:this.status
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.typelist=res.data.typelist;
					that.typeid=res.data.typeid;
					that.rscount=res.data.rscount;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=paotui&ajax=1",
				data:{
					typeid:this.typeid,
					status:this.status
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					 
					that.rscount=res.data.rscount;
				}
			})
		},
		setType:function(typeid){
			this.typeid=typeid;
			this.getPage();
		},
		accept:function(id){
			var that=this;
			if(confirm("确认接单吗")){
				$.ajax({
					url:"/sender.php?m=paotui&a=order&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						skyToast(res.message);
						that.getPage();
						
					}
				})
			}
			
		}
	}
})