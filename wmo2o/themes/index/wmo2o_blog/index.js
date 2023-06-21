var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"",
			list:[],
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getPage();
		},
		goBlog:function(id){
			window.location="/module.php?m=wmo2o_blog&a=show&id="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=wmo2o_blog&a=list&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
					that.list=res.data.list;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=wmo2o_blog&a=list&ajax=1",
				data:{
					type:that.type,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					} 
					
					that.per_page=res.data.per_page;
				}
			})
		},
	}
})