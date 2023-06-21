 
$(document).on("click", ".tabs-border-item", function() {
	var $group = $(this).parents(".tabs-border-group");
	var index = $(this).index();
	if ($group.length > 0) {
		$group.find(".tabs-border-box").removeClass("tabs-border-box-active");
		$group.find(".tabs-border-box").eq(index).addClass("tabs-border-box-active");
	}
	$(this).addClass("tabs-border-active").siblings().removeClass("tabs-border-active");
}) 
$(document).on("change","#gid",function(){
	var gid=$(this).val();
	$.get("/module.php?m=forum_group&a=catlist&ajax=1&gid="+gid,function(data){
		var html='<option value="0">请选择</option>';
	 
		for(var i=0;i<data.data.length;i++){
			html=html+'<option value="'+data.data[i].catid+'">'+data.data[i].title+'</option>';
		}
		console.log(html);
		$("#catid").html(html);
	},"json")
})
var inSubmit=false;
$(document).on("click","#submit",function(){
	var form=$(this).parents("form");
	var imgs=$(".uploader-imgsdata-img");
	if(inSubmit){
		return false;
	} 
	inSubmit=true;
	setTimeout(function(){
		inSubmit=false;
	},2000);
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
		if(!res.error){
			setTimeout(function(){
				goBack();
			},1000)
			
		}
	},"json")
})
$(document).on("click", "#mp4Up-btn", function() {
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
	var file = document.querySelector("#up-video").files[0];

	if (file == undefined) {
		console.log("Empty");
		return false;
	}
	if(UPLOAD_OSS=='' || UPLOAD_OSS=="0"){
		fdata.append("upimg", file);
		upVideo(fdata);
		return false;
	}
	fdata.append("file", file);
	$.get("/index.php?m=ossupload&a=auth&ajax=1", function(data) {
		
	
		fdata.append("OSSAccessKeyId", data.accessid);
		fdata.append("policy", data.policy);
		fdata.append("Signature", data.sign);
		fdata.append("key", data.key + file.name);
		fdata.append("callback", data.callback);

		
		
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

function upVideo(fdata){
	$.ajax({
		url: "/index.php?m=upload&a=UploadMp4&ajax=1",
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
			$("#mp4url").val(data.imgurl);
			var html = '<video controls="" src="' + data.trueimgurl + '" class="video"></video>';
			$("#mp4box").html(html);

		},
		error: function(returndata) {
			console.log(returndata);

		}
		});
}	
