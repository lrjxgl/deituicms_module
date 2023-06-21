var App=new Vue({
	el:"#App",
	data:function(){
		return {
			catid:0,
			list:[],
			isFirst:true,
			per_page:0,
			gid:gid,
			ssuser:{},
			taglist:[]
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getPage();
		}
		
	},
	watch:{
		list:function(n,o){
			this.setCache(); 
		}
	},
	methods:{
		setCache:function(){
			var value=this.$data
			sessionStorage.setItem("page_group_title",JSON.stringify(value));
		},
		getCache:function(){
			var res=sessionStorage.getItem("page_group_title");
			console.log(res);
			if(res==null){
				return false;
			}
			value=JSON.parse(res)
			if(gid!=value.gid){
				return false;
			}
			this.catid=value.catid;
			this.list=value.list;
			this.isFirst=value.isFirst;
			this.per_page=value.per_page;
			this.gid=value.gid;
			this.ssuser=value.ssuser;
			this.taglist=value.taglist;
			return true;
		},
		setCat:function(catid){
			this.catid=catid;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_title&ajax=1&gid="+this.gid,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.ssuser=res.data.ssuser;
					that.taglist=res.data.taglist;
					that.per_page=res.data.per_page;
					that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=group_title&ajax=1&gid="+this.gid,
				data:{
					per_page:this.per_page,
					tagid:this.catid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list);
						}
					}
					that.per_page=res.data.per_page;
					
					 
				}
			})
		}
	}
});