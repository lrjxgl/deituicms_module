var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			myList:[]
		}
	},
	created:function(){
		
		this.getPage();
		this.getMy(); 
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_tags&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					res.data.list=that.parseList(res.data.list);
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		 
		parseList:function(list){
			for(var i in list){
				list[i].choice=0;
			}
			return list;
		},
		getMy:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sjsj_tags&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					res.data.list=that.parseList(res.data.list);
					that.myList=res.data.list;
				}
			})
		},
		choice:function(item){
			if(item.choice==1){
				item.choice=0;
			}else{
				item.choice=1;
			}
		},
		follow:function(){
			var tagids=[]
			for(var i in this.list){
				if(this.list[i].choice==1){
					tagids.push(this.list[i].tagid);
				}
			}
			if(tagids.length==0){
				return false;
			}
			 
			var that=this;
			skyJs.confirm({
				content:"确认关注标签吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=sjsj_tags&a=toggle&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							tagids:tagids.toString()
						},
						success:function(res){
							if(res.error){
								return false;
							}
							skyToast(res.message) 
							that.getMy();
							that.getPage();
						}
					})
				}
			})
			
		},
		unFollow:function(){
			var tagids=[]
			for(var i in this.myList){
				if(this.myList[i].choice==1){
					tagids.push(this.myList[i].tagid);
				}
			}
			if(tagids.length==0){
				return false;
			}
			 
			var that=this;
			skyJs.confirm({
				content:"确认取消关注标签吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=sjsj_tags&a=toggle&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							tagids:tagids.toString()
						},
						success:function(res){
							if(res.error){
								return false;
							}
							skyToast(res.message) 
							that.getMy(); 
							that.getPage();
						}
					})
				}
			})
		}
	}
})