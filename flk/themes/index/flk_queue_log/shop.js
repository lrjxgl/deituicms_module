var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_queue_log&a=shop&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					} 
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=flk_queue_log&a=shop&ajax=1",
				dataType:"json",
				data:{
					per_page:that.per_page,
					shopid:shopid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					} 
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					that.per_page=res.data.per_page;
				}
			})
		},
	}
})