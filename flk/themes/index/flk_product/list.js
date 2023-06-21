var isFirst=false;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"",
			per_page:0,
			catid:0,
			scatid:0,
			catlist:[],
			list:[]
		}
	},
	created:function(){
		this.catid=catid;
		this.scatid=catid;
		this.getPage();
		
	},
	methods:{
		setCatid:function(catid){
			this.catid=catid;
			isFirst=true;
			this.per_page=0;
			this.getList();
		},
		goProduct:function(id){
			window.location="/module.php?m=flk_product&a=show&id="+id
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_product&a=list&ajax=1&catid="+this.catid,
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					that.catlist=res.data.catList;
					 
					that.list=res.data.list;
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
				url:"/module.php?m=flk_product&a=list&ajax=1&catid="+this.catid,
				data:{
					type:that.type,
					per_page:that.per_page,
					catid:this.catid
				},
				dataType:"json",
				success:function(res){
					that.per_page=res.data.per_page;
					if(isFirst){
						that.list=res.data.list;
					}else{
					 
						for(var i=0;i<res.data.list.length;i++){
							that.list.push(res.data.list[i]);
						}
		
					}
					isFirst=false;
				}
			})
		}
	}
})