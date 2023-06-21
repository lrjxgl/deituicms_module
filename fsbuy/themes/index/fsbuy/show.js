var oApp=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			phList:[]
		} 
	},
	created:function(){
		this.getPage();
		this.getPaihang();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.get("/module.php?m=fsbuy&a=orderlist&fsid="+fsid,function(res){
				that.list=res.data.list;
			},"json")
		},
		getPaihang:function(){
			var that=this;
			$.get("/module.php?m=fsbuy&a=inviteph&fsid="+fsid,function(res){
				that.phList=res.data.list;
			},"json")
		}
	}
})