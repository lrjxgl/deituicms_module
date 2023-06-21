var isFirst=false;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			per_page:0,
			type:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsbuy&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !isFirst){
				skyToast("已经到底了");
				return false;
			}
			$.ajax({
				url:"/module.php?m=fsbuy&ajax=1",
				data:{
					type:that.type,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					that.per_page=res.data.per_page;
					if(isFirst){
						that.pageData.list=res.data.list;
					}else{
						var list=that.pageData.list;
						for(var i=0;i<res.data.list.length;i++){
							list.push(res.data.list[i]);
						}
						that.pageData.list=list;
					}
					isFirst=false;
				}
			})
		}
	}
})