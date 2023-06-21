var per_page=0,isFirst=true;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"hot"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sblog_topic&ajax=1",
				data:{
					type:that.type
				},
				method:"GET",
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
					per_page=res.data.per_page;
					isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(per_page==0 && !isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=sblog_topic&ajax=1",
				data:{
					type:that.type,
					per_page:per_page
				},
				method:"GET",
				dataType:"json",
				success:function(res){
					per_page=res.data.per_page;
					if(isFirst){
						isFirst=false;
						that.pageData.topicList=res.data.topicList;
					}else{
						var pageData=that.pageData;
						var topicList=pageData.topicList;
						for(var i in res.data.topicList){
							topicList.push(res.data.topicList[i]);
						}
						that.pageData.topicList=topicList;
					}
				}
			})
		},
		setType:function(type){
			this.type=type;
			per_page=0;
			isFirst=true;
			this.getList();
		},
		goTopic:function(id){
			window.location="/module.php?m=sblog_topic&a=show&id="+id;
		}
	}
});