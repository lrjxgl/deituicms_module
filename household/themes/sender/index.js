var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			catList:{},
			catid:0,
			cityid:0,
			city:"厦门"
		}
	},
	created:function(){
		this.getPage();
		if(localStorage.getItem("cityid")){
			this.cityid=localStorage.getItem("cityid");
			this.city=localStorage.getItem("city");
		}
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=household&a=list&ajax=1",
				data:{
					catid:this.catid,
					cityid:this.cityid
				},
				dataType:"json",
				success:function(res){
					console.log(res)
					that.list=res.data.list;
					that.catList=res.data.catList;
					
					that.catid=res.data.catid;
				}
			})
		},
		setType:function(catid){
			this.catid=catid;
			this.getPage();
		},
		accept:function(orderid){
			var that=this;
			skyJs.confirm({
				content:"确认好地点接单吗",
				success:function(){
					$.ajax({
						url:"/sender.php?m=household&a=order&ajax=1&orderid="+orderid,
						dataType:"json",
						success:function(res){
							skyToast(res.message);
							that.getPage();
							
						}
					})
				}
			})
			 
			
		}
	}
})