$(document).on("click","#showOrder",function(){
	$("#orderModal").show();
})

$(document).on("click","#orderSubmit",function(){
	var that=this;
	if(!postCheck.canPost()){
		return false;
	}
	$.ajax({
		url:"/module.php?m=lltuan_order&a=order&ajax=1",
		dataType:"json",
		type:"POST",
		data:$("#orderForm").serialize(),
		success:function(res){
			
			if(res.error){
				skyToast(res.message);
				return false;
			}
			skyJs.alert({
				content:"恭喜下单成功,之后我们会电话确认"
			})
			$("#orderModal").hide();
		}
	})
})

$(document).on("click","#showShare",function(){
	$("#shareModal").show();
})