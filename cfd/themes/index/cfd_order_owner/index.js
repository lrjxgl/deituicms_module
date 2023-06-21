var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			type:"all"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(t){
			var that=this;
			that.type=t;
			that.per_page=0;
			that.isFirst=true;
			that.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=cfd_order_owner&ajax=1",
				dataType:"json",
				data:{
					cfdid:cfdid
				},
				success:function(res){
					that.list=res.data.data;
					that.isFirst=false;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=cfd_order_owner&ajax=1",
				dataType:"json",
				data:{
					type:that.type,
					per_page:that.per_page,
					cfdid:cfdid
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.data;
						that.isFirst=false;
					}else{
						for(var i in res.data.data){
							that.list.push(res.data.data[i]);
						}
					}
					
					that.per_page=res.data.per_page;
				}
			})
		}
	}
})