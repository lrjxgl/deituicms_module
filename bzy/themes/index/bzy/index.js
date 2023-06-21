var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:[],
			isFirst:true,
			per_page:0,
			type:"doing"
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
			this.getList();
			
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=bzy_event&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.type
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=bzy_event&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					per_page:this.per_page
				},
				success:function(res){
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					
				}
			})
		},
		goDetail:function(eventid){
			window.location="/module.php?m=bzy_event&a=show&eventid="+eventid
		}
	}
})