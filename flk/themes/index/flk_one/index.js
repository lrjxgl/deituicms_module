var App=new Vue({
	el:"#App",
	data:function(){
		return {
			tab:"doing",
			list:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.tab
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		setTab:function(t){
			this.tab=t;
			this.getList();
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.tab
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goDetail:function(id){
			window.location="/module.php?m=flk_one&a=show&id="+id
		}
	}
})