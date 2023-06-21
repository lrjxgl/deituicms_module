var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			sjShow:false,
			sitem:{},
			sj_money:0,
			sj_content:""
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_news&a=mybuy&ajax=1",
				data:{
					catid:this.catid
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
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=sjsj_news&a=mybuy&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		goDetail:function(item){
			window.location="/module.php?m=sjsj_news&a=show&newsid="+item.newsid
		},
		showFinish:function(item){
			this.sitem=item;
			this.sjShow=true;
		},
		finish:function(){
			var that=this;
			skyJs.confirm({
				content:"确认打赏"+that.sj_money+"元吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=sjsj_news&a=finish&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							newsid:that.sitem.newsid,
							money:that.sj_money,
							content:that.sj_content
						},
						success:function(res){
							skyToast(res.message)
							if(res.error){
								return false;
							}
							that.sjShow=false;
						}
					})
				}
			})
		},
	}
})