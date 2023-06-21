var App=new Vue({
	el:"#app",
	data:function(){
		return {
			type:"",
			list:[],
			isFirst:true,
			per_page:0
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
				url:"/module.php?m=exue_shop&a=api&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.isFirst=false;
					that.list=res.data.list;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_shop&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					per_page:this.per_page,
					lat:gps.lat,
					lng:gps.lng
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
							that.isFirst=false;
						}
					}
					that.per_page=res.data.per_page;
				}
			})
		}
	}
})