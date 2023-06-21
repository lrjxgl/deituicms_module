var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{},
			pageLoad:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=flk_product_ks&a=list&ajax=1&productid="+productid,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		}
	}
})

$(document).on("click",".ks-add",function(){
	var $obj=$(this).parents(".ksitem");
	if($obj.find(".ks-title").val()=='' || $obj.find(".ks-price").val()=='' ){
		skyToast('请完善信息');
		return false;
	}
	$.post("/moduleadmin.php?m=flk_product_ks&a=save&ajax=1",{
		productid:productid,
		id:$obj.find(".ks-id").val(),
		size:$obj.find(".ks-size").val(),
		title:$obj.find(".ks-title").val(),
		price:$obj.find(".ks-price").val(),
		total_num:$obj.find(".ks-total-num").val(),
		buy_num:$obj.find(".ks-buy-num").val()
	},function(data){
			skyToast('保存成功');
			App.getPage();
	},"json")
});

$(document).on("click",".ks-del",function(){
	if(confirm('确认删除吗？')){
		var $obj=$(this).parents(".ksitem");
		$.get("/moduleadmin.php?m=flk_product_ks&a=delete&ajax=1&id="+$obj.find(".ks-id").val(),function(data){
			App.getPage();
		})
	}
});