 
var parent=$(".uploader-imgsdata-imgslist");
var totalNum=0;
$(document).on("click",".js-upimg-btn",function(){
	wx.chooseImage({
	  count: 9, // 默认9
	  sizeType: ['original', 'compressed'], 
	  sourceType: ['album', 'camera'], 
	  success: function (res) {
			var localIds = res.localIds;
		 
			for(var i=0;i<localIds.length;i++){
				wx.uploadImage({
				  localId: localIds[i],  
				  isShowProgressTips: 1,  
				  success: function (res2) {
				    var serverId = res2.serverId;
					
					lazyUpload(serverId);
				  }
				});
			} 
	  }
	});
	 
})
 
function lazyUpload(media_id){
	
	$.ajax({
		url:"/index.php?m=upload&a=Downweixin",
		type:"POST",
		data:{
			media_id:media_id 
		},
		dataType:"json",
		success:function(data){
			
			if(data.error){
				skyToast(data.message);
				return false;
			} 
			var html = '<div class="upimg-item uploader-imgsdata-img js-zzimg" data-src="' + data.trueimgurl + '"  v="' +
				data.imgurl + '" trueimg="' + data.imgurl + '">' +
				'	<img class="upimg-img" src="' + data.trueimgurl + '.100x100.jpg"/>' +
				'<i class="upimg-del js-imgdel"></i>' +
				'<div class="flex flex-center">'+
				'	<div class="upimg-goleft">&lt;</div>'+
				'	<div class="upimg-goright">&gt;</div>'+
				'</div>'+
			'</div>';
			 
			parent.append(html);
			//同步到表单
			var $imgs=$(".uploader-imgsdata-img");
			var $imgsdata="";
			for(var i=0;i<$imgs.length;i++){
				if(i>0){
					$imgsdata+=","
				}
				$imgsdata+=$imgs.eq(i).attr("v");
				
			}
			$("#imgsdata").val($imgsdata); 
		},
		
		error:function(e){
			alert(e.responseText)
		}
		
	})
	 
}

function uploaderDataSync(){
	//同步到表单
	var $imgs=$(".uploader-imgsdata-img");
	console.log($imgs.length)
	var $imgsdata="";
	for(var i=0;i<$imgs.length;i++){
		if(i>0){
			$imgsdata+=","
		}
		$imgsdata+=$imgs.eq(i).attr("v");
		
	}
	$("#imgsdata").val($imgsdata);
} 

 $(document).on("click", ".js-imgdel", function() {
 	var id = $(this).parents(".uploader-imgsdata-img").remove();
 })
 
 $(document).on("click", ".upimg-goleft", function() {
 	var p = $(this).parents(".upimg-item");
 	if (p.index(".upimg-item") == 0) {
 		return false;
 	}
 	p.prev().before(p);
 	uploaderDataSync()
 })

 $(document).on("click", ".upimg-goright", function() {
 	var p = $(this).parents(".upimg-item");
 	p.next().after(p);
 	uploaderDataSync()
 })
