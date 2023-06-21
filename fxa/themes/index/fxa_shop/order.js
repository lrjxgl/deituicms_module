var App=new Vue({
	el:"#App",
	data:function(){
		return {
			isFirst:true,
			per_page:0,
			list:[],
			rscount:0,
			type:"all"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxa_shop&a=order&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.rscount=res.data.rscount;
					that.per_page=res.data.per_page;
				}
			})
		},
		setType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxa_shop&a=order&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					type:this.type
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var  i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page=res.data.per_page;
					that.rscount=res.data.rscount;
				}
			})
		},
	}
})