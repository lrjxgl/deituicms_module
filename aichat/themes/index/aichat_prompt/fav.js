var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0
		}
	},
	created:function(){
		//this.catid=catid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_prompt&a=fav&ajax=1",
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
					that.$nextTick(function(){
						var cp=new ClipboardJS('.js-copy');
						cp.on("success",function(){
							skyToast("复制成功")
						})
					})
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=aichat_prompt&a=fav&ajax=1",
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
					
					that.$nextTick(function(){
						var cp=new ClipboardJS('.js-copy');
						cp.on("success",function(){
							skyToast("复制成功")
						})
					})
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		favToggle:function(item){
			
			var that=this;
			$.ajax({
				url:"/index.php?m=fav&a=toggle&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					objectid:item.id,
					tablename:"mod_aichat_prompt"
				},
				success:function(e){
					if(e.data=="delete"){
						item.isfav=0;
					}else{
						item.isfav=1;
					}
				}
			})
		}
	}
})