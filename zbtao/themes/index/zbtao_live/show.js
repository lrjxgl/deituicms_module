var App=new Vue({
	el:"#App",
	data:function(){
		return {
			data:{},
			ppid:0,
			pp:{},
			liveid:0,
			 proList:[]
		}
	},
	created:function(){
		this.liveid=liveid;
		this.getPage();
	},
	
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_live&a=show&ajax=1",
				data:{
					liveid:this.liveid
				},
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
					that.pp=res.data.pp;
					that.ppid=that.pp.ppid;
					that.proList=res.data.proList;
				}
			})
		},
		ppFollow:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp&a=followToggle&ajax=1",
				data:{
					ppid:this.ppid
				},
				dataType:"json",
				success:function(res){
					that.pp.isFollow=res.data.isFollow;
				}
			})
		},
		liveFollow:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_live&a=followToggle&ajax=1",
				data:{
					liveid:this.liveid
				},
				dataType:"json",
				success:function(res){
					that.data.isFollow=res.data.isFollow;
				}
			})
		},
		proFollow:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_live_product&a=followToggle&ajax=1",
				data:{
					productid:item.productid
				},
				dataType:"json",
				success:function(res){
					item.isFollow=res.data.isFollow;
				}
			})
		},
	}
})