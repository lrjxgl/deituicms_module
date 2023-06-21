var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp_tag&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		toggleSelect:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp_tag&a=toggle&ajax=1",
				dataType:"json",
				data:{
					tagid:item.tagid
				},
				success:function(res){
					that.getPage();
				}
			})
		}
	}
})