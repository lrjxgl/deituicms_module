var isFirst=true,per_page=0;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
		}
	},
	created:function(){
		
	},
	methods:{
		goDetail:function(id){
			window.location="/module.php?m=shopmap&a=show&id="+id;
		},
		getList:function(){
			var that=this;
			if(!isFirst && per_page==0){
				skyToast("暂无更多内容")
				return false;
			}
			$.ajax({
				url:"/module.php?m=shopmap&a=near&ajax=1",
				data:{
					lat:lat,
					lng:lng,
					per_page:per_page
				},
				dataType:"json",
				success:function(res){
					per_page=res.data.per_page;
					if(isFirst){
						that.pageData=res.data;
					}else if(res.data.list.length>0){
						var list=that.pageData.list;
						for(var i in res.data.list){
							list.push(res.data.list[i]);
						}
						that.pageData.list=list;
					}
					isFirst=false;
					that.pageLoad=true;
				}
			})
		}
	}
})