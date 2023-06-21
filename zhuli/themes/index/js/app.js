  $(document).on("click",".vtoggle",function(){
		if($(this).hasClass("von")){
			$(this).removeClass("von");
		}else{
			$(this).addClass("von");
		}
	});