 
$(function() {
	$(document).on("click", ".js-fav-btn", function() {
		var id = $(this).attr("v");
		var that = $(this);
		$.get("/index.php?m=fav&a=save&ajax=1&tablename=taoke&object_id=" + $(this).attr("v"), function(data) {

			that.html(data.message);
		}, "json")
	})
})
