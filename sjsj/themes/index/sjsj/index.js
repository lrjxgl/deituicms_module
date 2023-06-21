var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			type:"recommend",
			scf:{}
		}
	},
	created:function(){
		//this.catid=catid;
		this.getPage();
		this.getConfig();
	},
	methods:{
		
		setType:function(t){
			this.type=t;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_news&a=list&ajax=1",
				data:{
					type:this.type
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
				url:"/module.php?m=sjsj_news&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					type:this.type
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
		getConfig:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_config&ajax=1",
				 
				dataType:"json",
				success:function(res){
					that.scf=res.data.scf;
				}
			})
		},
		buy:function(item){
			skyJs.confirm({
				content:"确认花"+this.scf.sold_money+"元买断吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=sjsj_news&a=buy&ajax=1",
						dataType:"json",
						data:{
							newsid:item.newsid
						},
						success:function(res){
							skyToast(res.message)
						}
					})
				}
			})
		},
		goGuest:function(item){
			window.location="/module.php?m=sjsj_guest&a=user&newsid="+item.newsid
		}
	}
})