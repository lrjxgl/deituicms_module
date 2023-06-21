function getList(){
	$.get("/moduleadmin.php?m=imgdiy_item&ajax=1&imgid="+id,function(res){
		var html=template("list-tpl",res.data); 
		$("#list").html(html);
	},"json")
}
function loadImg(){
	$("#sbImg-load").attr("src","/moduleadmin.php?m=imgdiy&a=img&id="+id+"&t="+Math.random());
}
function finish(){
	$.get("/moduleadmin.php?m=imgdiy&a=phpimg&id="+id,function(res){
		goBack();
	})
}
$(function(){
	getList();
	setTimeout(function(){
		loadImg();
	},1000)
	$(document).on("click",".addItem-close",function(){
		var id=$(this).attr("v");
		$.get("/moduleadmin.php?m=imgdiy_item&a=delete&ajax=1&id="+id,function(res){
			getList();
			loadImg();
		},"json")
	})
	$(document).on("click","#addTabs .item",function(){
		var index=$(this).index();
		console.log(index);
		$(this).addClass("active").siblings().removeClass("active");
		$("#addBox .addItem").hide();
		$("#addBox .addItem:eq("+index+")").show();
	})
	
	$(document).on("click",".js-addSubmit",function(){
		var form=$(this).parents("form");
		$.post("/moduleadmin.php?m=imgdiy_item&a=save&ajax=1",form.serialize(),function(res){
			getList();
			loadImg();
		},"json")
	})
	
	 
	
})