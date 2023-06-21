
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"",
			keyword:"",
			goldModalShow:false,
			sendGoldNum:0,
			sendGoldId:0,
			per_page:0,
			isFirst:true,
			list:[]
		}
	},
	created:function(){
		this.type=type;
		this.getPage();
	},
	methods:{
		search:function(){
			this.getPage();
		},
		setType:function(type){
			this.type=type;
			isFirst=true;
			per_page=0;
			this.keyword="";
			this.getPage();
		},
		addGold:function(item){
			this.sendGoldId=item.id;
			this.goldModalShow=true;
		},
		hideGoldModal:function(){
				this.goldModalShow=false;
		},
		sendGold:function(){
			var that=this;
			this.goldModalShow=false;
			$.ajax({
				url:"/moduleadmin.php?m=wmo2o_blog&a=sendgold&ajax=1",
				data:{
					gold:this.sendGoldNum,
					id:this.sendGoldId
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					 
					for(var i=0;i<that.list.length;i++){
						if(that.list[i].id==that.sendGoldId){
							that.list[i].gold=res.data
						}	
					}
				}
			})
		},
		goBlog:function(id){
			var html='<iframe class="iframe" style="border:0;width:420px;height:420px;" src="/module.php?m=wmo2o_blog&a=show&id='+id+'"></iframe>';
			showbox("网页查看",html,480);
			 
		},
		del:function(id){
			var that=this;
			if(confirm("删除后不可恢复，确认删除吗？")){
				$.ajax({
					url:"/moduleadmin.php?m=wmo2o_blog&a=delete&ajax=1&id="+id,
				
					dataType:"json",
					success:function(res){
						var list=that.list;
						var newlist=[];
						for(var i=0;i<list.length;i++){
							if(list[i].id!=id){
								newlist.push(list[i]);
							}
						}
						that.list=newlist;
						 
					}
				})
			}
			
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=wmo2o_blog&ajax=1",
				data:{
					type:this.type,
					keyword:that.keyword
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			
			$.ajax({
				url:"/moduleadmin.php?m=wmo2o_blog&ajax=1",
				data:{
					type:this.type,
					keyword:that.keyword,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					if(that.isFirst){
						that.isFirst=false;
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page=res.data.per_page;
				}
			})
		}
	}
})