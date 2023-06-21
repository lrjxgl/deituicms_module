function getVideoBg(mp4, callback) {
	var video;
	var scale = 0.8;
	var callback = callback;
	video = document.createElement("video");
	video.src = window.URL.createObjectURL(mp4);
	video.width = 480;
	video.height = 320;
	video.addEventListener('loadeddata', function() {
		var canvas = document.createElement("canvas");
		canvas.width = video.videoWidth * scale;
		canvas.height = video.videoHeight * scale;
		var context = canvas.getContext('2d');
		context.drawImage(video, 0, 0, canvas.width, canvas.height);
		var base64 = canvas.toDataURL("image/png");
		$.post("/index.php?m=upload&a=base64", {
			content: base64
		}, function(data) {

			callback(data.trueimgurl);
		}, "json")

	});

}
$(document).on("click", "#js-upbtn", function() {
	$("#up-video").click();
})

function onprogress(evt) {
	var loaded = evt.loaded; //已经上传大小情况 
	var tot = evt.total; //附件总大小 
	var per = Math.floor(100 * loaded / tot); //已经上传的百分比 
	$("#progress").html(per + "%");
	$("#progress").css("width", per + "%");
	if (per >= 100) {
		$("#progress").hide();
	}
}

$(document).on("change", "#up-video", function() {
	var fdata = new FormData();

	$.get("/moduleshop.php?m=flk_ossupload&a=auth&ajax=1", function(data) {
		var file = document.querySelector("#up-video").files[0];

		if (file == undefined) {
			console.log("Empty");
			return false;
		}
		
		fdata.append("OSSAccessKeyId", data.accessid);
		fdata.append("policy", data.policy);
		fdata.append("Signature", data.sign);
		fdata.append("key", data.key + file.name);
		fdata.append("callback", data.callback);

		fdata.append("file", file);
		$.ajax({
			url: data.url,
			type: 'POST',
			data: fdata,
			contentType: false,
			processData: false,
			dataType: "json",
			xhr: function() {
				var xhr = $.ajaxSettings.xhr();
				if (onprogress && xhr.upload) {
					xhr.upload.addEventListener("progress", onprogress, false);
					return xhr;
				}
			},
			success: function(data) {
				console.log(data);
				$("#url").val(data.filename);
				var html = '<video controls="" src="' + data.truename + '" class="video"></video>';
				$("#mp4box").html(html);

			},
			error: function(returndata) {
				console.log(returndata);
				 
			}
		});
	}, "json")

})

$(document).on("click", "#submit", function() {

	$.post("/shopadmin.php?m=shop_videos&a=save&ajax=1", $("#form").serialize(), function(data) {
		mui.toast(data.message);
		goBack();
	}, "json")
})
