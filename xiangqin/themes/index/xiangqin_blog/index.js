var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"recommend"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.getPage();
		},
		goBlog:function(id){
			window.location="/module.php?m=xiangqin_blog&a=show&id="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sblog_blog&a=list&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		}
	}
})