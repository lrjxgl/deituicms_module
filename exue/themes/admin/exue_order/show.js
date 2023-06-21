$(function(){
		$(document).on("click","#xy-btn",function(){
			$("#xy-box").show();
		})
		$(document).on("click","#xy-submit",function(){
			var  stid=$(".stid:checked").val();
			$.ajax({
				url:"/module.php?m=exue_order&a=bindStudent&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					orderid:orderid,
					stid:stid
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
		$(document).on("click","#bd-btn",function(){
			if(!confirm("确认已到学校报到乐吗?")){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_order&a=BaoMing&ajax=1",
		 
				dataType:"json",
				data:{
					orderid:orderid
				},
				success:function(res){
					skyToast(res.message)
					if(res.error){
						
						return false;
					}
					window.location.reload(); 
				}
			})
		})
	})