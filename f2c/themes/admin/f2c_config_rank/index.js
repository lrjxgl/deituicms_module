var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false
		}
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=f2c_config_rank&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.pageData=res.data;
				}
			})
		},
		addSave:function(){
			var that=this;
			var obj=$(".js-add-item");
			var title=obj.find('[name="title"]').val();
			var min_grade=obj.find('[name="min_grade"]').val();
			var max_grade=obj.find('[name="max_grade"]').val();
			var discount=obj.find('[name="discount"]').val();
			$.post("/moduleadmin.php?m=f2c_config_rank&a=save&ajax=1",{
				title:title,
				min_grade:min_grade,
				max_grade:max_grade,
				discount:discount
			},function(res){
				that.getList();
			},"json")
		}
	}
})
$(document).on("click",".js-save",function(){
	var obj=$(this).parents(".js-item");
	var title=obj.find('[name="title"]').val();
	var min_grade=obj.find('[name="min_grade"]').val();
	var max_grade=obj.find('[name="max_grade"]').val();
	var discount=obj.find('[name="discount"]').val();
	var id=$(this).attr("vid");
	$.post("/moduleadmin.php?m=f2c_config_rank&a=save&ajax=1",{
		id:id,
		title:title,
		min_grade:min_grade,
		max_grade:max_grade,
		discount:discount
	},function(res){
		App.getList();
	},"json")
})

$(document).on("click",".js-dele",function(){
	var url=$(this).attr("url");
	 
	if(confirm("删除后不可恢复，确认删除？")){
		$.get(url,function(res){
			App.getList();
		},"json")
	}	
})