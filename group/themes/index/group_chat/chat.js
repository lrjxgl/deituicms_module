var wshost="ws://www.fd175.com:8383";
function replace_img(str){
	return str.replace(/\[:img-(\d+)\]/g ,"<img class=\"msg-r-img js-bigimg\" src=\"/index.php?m=attach&a=getimg&type=100&id=$1\" bsrc=\"index.php?m=attach&a=getimg&type=base&id=$1\">");
}
function replace_msg(str){
	 
	str=replace_img(str);

	return str;
}
$(function(){
	
	 
	$(document).on("click","#up-img-btn",function(){
		$("#up-img-f").trigger("click");
	});
 
	
	$(document).on("change","#up-img-f", function(e){
	            var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
	            skyToast("上传图片中...");
	            for (var i = 0, len = files.length; i < len; ++i) {
	                var file = files[i];
	
	                if (url) {
	                    src = url.createObjectURL(file);
	                } else {
	                    src = e.target.result;
	                }
					lrz(file,{width:1024}) .then(function(rst){
						 
						$.post("/index.php?m=upload&a=base64",
						{
							content:rst.base64,
							tablename:"kefu",
							object_id:0,
							inattach:1
						},
						function(data){
							//console.log(data);		 
							if(data.error!=""){
								$("#sayCon").val(data.error);
							}else{
								$("#sayCon").val($("#sayCon").val()+"[:img-"+data.attach_id+"]");
							}
						},"json")
					})
					.catch(function(err){
						console.log(err)
					})
	                
	            }
	});
	
	
	$(document).on("click",".js-bigimg",function(e){
		e.stopPropagation();
		var html='<img id="js-bigImg" src="'+$(this).attr("bsrc")+'" style="max-width:100%;">';
		var w=$(window).innerHeight();
		var h=$(window).innerWidth();
		showbox("大图查看",html,w,h);
	});
	
	$(document).on("click","#js-bigImg",function(){
		showboxClose();
	})
	
	$(document).on("focusin","#sayCon",function(){
		if(window.screen.width <500){
			//$(".sayInput").addClass("fixInput");
		}
		
	})
	$(document).on("focusout","#sayCon",function(){
		setTimeout(function(){
		//	$(".sayInput").removeClass("fixInput");
		},300)
		
	})

})
