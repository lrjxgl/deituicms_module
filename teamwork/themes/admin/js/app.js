$(document).on("click",".js-refresh",function(){
	window.location.reload();
})
$(document).on("click",".delete,.js-delete",function(){
	var obj=$(this);
	if(confirm("删除后不可恢复，确认删除吗?")){
		$.get($(this).attr("url")+"&ajax=1",function(data){
			if(data.error=='0'){
				obj.parents("tr").remove();
			}else{
				alert(data.message);
			}
		},"json");
		
	}
})