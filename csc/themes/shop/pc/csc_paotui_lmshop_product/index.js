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
				url:"/moduleshop.php?m=csc_paotui_lmshop_product&ajax=1&lmid="+lmid,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		add:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_paotui_lmshop_product&a=save&ajax=1&lmid="+lmid,
				dataType:"json",
				type:"POST",
				data:$("#addForm").serialize(),
				success:function(res){
					$("#addForm [name='title']").val("")
					$("#addForm [name='price']").val("")
					that.getPage();
				}
			})
		},
		update:function(productid){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_paotui_lmshop_product&a=save&ajax=1&lmid="+lmid,
				dataType:"json",
				type:"POST",
				data:$("#e"+productid).serialize(),
				success:function(res){
					that.getPage();
				}
			})
		}
	}
})