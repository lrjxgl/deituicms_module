var App=new Vue({
	el:"#App",
	data:function(){
		return {
			group:{},
			gid:0,
			page:"chat",
			newsList:[]
		}
	},
	created:function(){
		this.gid=gid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_home&ajax=1",
				dataType:"json",
				data:{
					gid:this.gid
				},
				success:function(res){
					that.group=res.data.group;
					that.newsList=res.data.newsList;
				}
			})
		},
		setPage:function(page){
			this.page=page;
		},
		goNews:function(id){
			window.location="/module.php?m=zupu_news&a=show&id="+id
		},
		showMap:function(){
			var w=$(window).width();
			var h=$(window).innerHeight();
			var url="/index.php?m=map&a=show&map_com=baidu&lat="+this.group.lat+"&lng="+this.group.lng;
			var html='<iframe style="border:0;width:'+(w-60)+'px;height:300px;" id="mapFrame" src="'+url+'"></iframe>'
			showbox("查看位置",html,w-20,400)
		}
	}
})