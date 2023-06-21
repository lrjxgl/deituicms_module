var that=this;
var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{}
		}
	},
	created:function(){
		this.getPage();
		that=this;
	},
	methods:{
		getPage:function(){
			$.ajax({
				url:"/moduleshop.php?m=wmo2o_open&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		}
	}
})