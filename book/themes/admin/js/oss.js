function onMp3Progress(evt){
	var loaded = evt.loaded;     //已经上传大小情况 
	var tot = evt.total;      //附件总大小 
	var per = Math.floor(100*loaded/tot);  //已经上传的百分比 
	$("#mp3progress").html( per +"%" );
	$("#mp3progress").css("width" , per +"%");
}

function onMp4Progress(evt){
　　	var loaded = evt.loaded;     //已经上传大小情况 
 	var tot = evt.total;      //附件总大小 
 	var per = Math.floor(100*loaded/tot);  //已经上传的百分比 
　	$("#progress").html( per +"%" );
 	$("#progress").css("width" , per +"%");
}

$(document).on("click","#upmp3-btn",function(){
	$("#upMp3").click();
})

$(document).on("click","#upmp4-btn",function(){
	$("#upMp4").click();
})
 
$(document).on("change","#upMp3",function(){
	var fdata=new FormData();
	var file=document.querySelector("#upMp3").files[0];
	console.log(file.type); 
	if(file==undefined){
		return false;
	}
	if(file.type!='audio/mp3'){
		return false;
	}
	$.get("/index.php/ossupload/auth",function(data){

		fdata.append("OSSAccessKeyId",data.accessid);
		fdata.append("policy",data.policy);
		fdata.append("Signature",data.sign);
		fdata.append("key",data.key + file.name); 
		fdata.append("callback",data.callback);
		
		fdata.append("file", file);
		$.ajax({  
          url: data.url ,  
          type: 'POST',  
          data: fdata,
          contentType: false,  
          processData: false, 
          dataType:"json",
	          　xhr: function(){
	　　　　　　var xhr = $.ajaxSettings.xhr();
	　　　　　　if(onMp3Progress && xhr.upload) {
	　　　　　　　　xhr.upload.addEventListener("progress" , onMp3Progress, false);
	　　　　　　　　return xhr;
	　　　　　　}
	　　　　}, 
          success: function (data) {  
              console.log(data);
            $("#mp3url").val(data.filename);
			var html='<audio controls="" src="'+data.truename+'" ></audio>';
			$("#mp3box").html(html);
          },  
          error: function (returndata) {
          	console.log(returndata);
              alert(returndata);  
          }  
    	}); 
	},"json")
	 
})
	
$(document).on("change","#upMp4",function(){
	var fdata=new FormData();
	var file=document.querySelector("#upMp4").files[0];	
	
		if(file==undefined){
			
			return false;
		}
		console.log(file.type);
		if(file.type!='video/mp4'){
			return false;
		}
	$.get("/index.php/ossupload/auth",function(data){

		fdata.append("OSSAccessKeyId",data.accessid);
		fdata.append("policy",data.policy);
		fdata.append("Signature",data.sign);
		fdata.append("key",data.key + file.name); 
		fdata.append("callback",data.callback);
		
		fdata.append("file", file);
		$.ajax({  
          url: data.url ,  
          type: 'POST',  
          data: fdata,
          contentType: false,  
          processData: false, 
          dataType:"json",
	          　xhr: function(){
	　　　　　　var xhr = $.ajaxSettings.xhr();
	　　　　　　if(onMp4Progress && xhr.upload) {
	　　　　　　　　xhr.upload.addEventListener("progress" , onMp4Progress, false);
	　　　　　　　　return xhr;
	　　　　　　}
	　　　　}, 
          success: function (data) {  
              console.log(data);
            $("#mp4url").val(data.filename);
			var html='<video controls="" src="'+data.truename+'" class="video"></video>';
			$("#mp4box").html(html);
          },  
          error: function (returndata) {  
              alert(returndata);  
          }  
    	}); 
	},"json")
	 
})