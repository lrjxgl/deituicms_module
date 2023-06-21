var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			actid:0,
			activity:{}
		}
	},
	created:function(){
		this.actid=actid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_activity&a=join&ajax=1",
				data:{
					actid:this.actid
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
					that.activity=res.data.activity;
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
				url:"/module.php?m=fsw_activity&a=join&ajax=1",
				data:{
					actid:this.actid,
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
		changeWeight:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_join&a=changeweight&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					joinid:item.joinid,
					weight:item.weight
				},
				success:function(res){
					that.getPage();
				}
			})
		},
		checkin:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认选手签到吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=fsw_join&a=check&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							joinid:item.joinid
						},
						success:function(res){
							that.getPage();
						}
					})
				}
			})
		},
		finish:function(){
			var that=this;
			skyJs.confirm({
				content:"确认结束比赛吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=fsw_activity&a=finish&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							actid:that.actid
						},
						success:function(res){
							that.getPage();
						}
					})
				}
			})
		}
	}
})