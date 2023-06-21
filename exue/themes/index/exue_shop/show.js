$(document).on("click", ".js-sfollow-toggle", function() {
	var that = $(this);
	$.ajax({
		url: "/module.php?m=exue_shop&a=toggleFollow&ajax=1",
		dataType: "json",
		data: {
			shopid: that.attr("shopid")
		},
		success: function(res) {
			if (res.error) {
				skyToast(res.message);
				return false;
			}
			if (res.data.isFollow == 1) {
				that.addClass("fixFollow-active");
				that.html("已关注")
			} else {
				that.removeClass("fixFollow-active");
				that.html("+关注")
			}
		}
	})
})
