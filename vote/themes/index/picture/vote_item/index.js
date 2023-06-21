var isFirst=true,per_page=0;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			bianhao:"",
			type:"hot"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			isFirst=true;
			per_page=0;
			this.getPage();
		},
		search:function(){
			this.type="hot";
			isFirst=true;
			per_page=0;
			this.getPage();
		},
		getPage:function(){
			var that=this;
			if(!isFirst && per_page==0) return false;
			$.ajax({
				url:"/module.php?m=vote_join&a=list&vid="+vid,
				data:{
					bianhao:that.bianhao,
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					per_page=res.data.per_page;
					isFirst=false;
				}
			});
		}
	}
});
listload.showload(function(){
	App.getPage();
})