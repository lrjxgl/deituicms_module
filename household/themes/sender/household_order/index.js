var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			typelist:{},
			typeid:"",
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=household_order&a=list&ajax=1",
				
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.typelist=res.data.typelist;
					that.typeid=res.data.typeid;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=household_order&a=list&ajax=1",
				data:{
					type:this.typeid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		setType:function(typeid){
			this.typeid=typeid;
			this.getList();
		}
	}
})