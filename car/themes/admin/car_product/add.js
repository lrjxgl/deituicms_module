var inSubmit=false;
			laydate.render({
				elem:"#pai_date"
			})
			$(document).on("click", ".tabs-border-item", function() {
				var $group = $(this).parents(".tabs-border-group");
				var index = $(this).index();
				if ($group.length > 0) {
					$group.find(".tabs-border-box").removeClass("tabs-border-box-active");
					$group.find(".tabs-border-box").eq(index).addClass("tabs-border-box-active");
				}
				$(this).addClass("tabs-border-active").siblings().removeClass("tabs-border-active");
			}) 
			$(document).on("click","#submit",function(){
				if(inSubmit){
					return false;
				}
				inSubmit=true;
				setTimeout(function(){
					inSubmit=false;
				},1000)
				var form=$(this).parents("form");
				var imgs=$(".uploader-imgsdata-img");
				 
				if(imgs.length>0){
					var s="";
					for(var i=0;i<imgs.length;i++){
						if(i>0){
							s+=",";
						}
						s+=imgs.eq(i).attr("v");		
					}
					$("#imgsdata").val(s);
				}
				 
				$.post(form.attr("action")+"&ajax=1",form.serialize(),function(res){
					skyToast(res.message);
					inSubmit=false;
					if(!res.error){
						goBack();
					}
				},"json")
			})
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

	$.get("/index.php?m=ossupload&a=auth&ajax=1", function(data) {
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
				$("#mp4url").val(data.filename);
				var html = '<video controls="" src="' + data.truename + '" class="video"></video>';
				$("#mp4box").html(html);

			},
			error: function(returndata) {
				console.log(returndata);

			}
		});
	}, "json")

})
