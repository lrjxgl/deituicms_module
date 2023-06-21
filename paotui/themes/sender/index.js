var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			typelist:{},
			typeid:0,
			cityid:0,
			city:"厦门",
			newMsg:0,
			time:0,
			pconfig:{}
		}
	},
	created:function(){
		var that=this;
		
		if(localStorage.getItem("cityid")){
			this.cityid=localStorage.getItem("cityid");
			this.city=localStorage.getItem("city");
		}
		this.getPage();
		that.getNew()
		setInterval(function(){
			that.getNew()
		},10000);
	},
	methods:{
		getNew:function(){
			var that=this;
			$.ajax({
				url:"sender.php?m=paotui&a=new&ajax=1",
				dataType:"json",
				data:{
					time:this.time
				},
				success:function(res){
					that.newMsg=res.data;
					console.log(that.newMsg)
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=paotui&a=list&ajax=1",
				data:{
					typeid:this.typeid,
					cityid:this.cityid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.typelist=res.data.typelist;
					that.typeid=res.data.typeid;
					that.time=res.data.time;
					that.pconfig=res.data.pconfig;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=paotui&a=list&ajax=1",
				data:{
					typeid:this.typeid,
					cityid:this.cityid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.time=res.data.time;
				}
			})
		},
		setType:function(typeid){
			this.typeid=typeid;
			this.getPage();
		},
		accept:function(id){
			var that=this;
			if(confirm("确认接单吗")){
				$.ajax({
					url:"/sender.php?m=paotui&a=order&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						skyToast(res.message);
						that.getPage();
						
					}
				})
			}
			
		}
	}
})
