$(document).on("click", ".js-pass", function() {
	var that = this;
	skyJs.confirm({
		content:"确认通过审核吗？",
		success:function(){
			$.ajax({
				url: "/moduleadmin.php?m=lltuan_order&a=pass&ajax=1",
				dataType: "json",
				data: {
					orderid: $(that).attr("v")
				},
				success: function(res) {
					skyToast(res.message)
					$(that).parents("tr").remove();
				}
			})
		}
	})
	
})

$(document).on("click", ".js-forbid", function() {
	var that = this;
	skyJs.confirm({
		content:"确认取消订单吗？",
		success:function(){
			$.ajax({
				url: "/moduleadmin.php?m=lltuan_order&a=forbid&ajax=1",
				dataType: "json",
				data: {
					orderid: $(that).attr("v")
				},
				success: function(res) {
					skyToast(res.message);
					$(that).parents("tr").remove();
				}
			})
		}
	})
	
})
