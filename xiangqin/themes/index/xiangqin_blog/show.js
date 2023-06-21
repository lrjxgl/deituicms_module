var App=new Vue({
	el:"#App",
	data:function(){
		return {
			id:0,
			 
			data:{},
			imgslist:[],
			islove:0,
			isfav:0,
			author:{}
		}
	},
	created:function(){
		this.id=id;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=sblog_blog&a=show&ajax=1&id="+this.id,
				dataType:"json",
				success:function(res){
					 
					that.data=res.data.data;
					that.imgslist=res.data.imgslist;
					that.author=res.data.author;
				}
			})
		}
	}
})