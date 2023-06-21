var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			catList:[],
			topCat:{},
			scList:[],
			sc_id:0,
			sc_title:"所在区域",
			keyword:""
		}
	},
	created:function(){
		this.catid=catid;
		this.getPage();
	},
	methods:{
		setArea:function(sc_id,title){
			this.sc_id=sc_id;
			this.sc_title=title;
			this.per_page=0;
			this.isFirst=true;
			$("#scarea-box").hide();
			this.getList();
		},
		setCat:function(catid){
			this.catid=catid;
			this.per_page=0;
			this.isFirst=true;
			 
			this.getList();
		},
		search:function(){
			this.per_page=0;
			this.isFirst=true;
			 
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fenlei&a=list&ajax=1",
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
					that.catList=res.data.catList;
					that.topCat=res.data.topCat;
					that.scList=res.data.scList;
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
				url:"/module.php?m=fenlei&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					sc_id:this.sc_id,
					keyword:this.keyword
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
		}
	}
})