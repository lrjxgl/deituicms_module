$(function(){
		$(document).on("click","#xy-btn",function(){
			$("#xy-box").show();
		})
		$(document).on("click","#xy-submit",function(){
			var  tcid=$(".tcid:checked").val();
			$.ajax({
				url:"/moduleshop.php?m=exue_order&a=bindTeacher&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					orderid:orderid,
					tcid:tcid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					$("#xy-box").hide();
				}
			})
		})
		 
	})