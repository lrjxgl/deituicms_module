function newNotice(){
	newOrder();
	setTimeout(function(){
		newNotice()
	},60000)
}
setTimeout(function(){
	newNotice()
},60000);

var audioOrder=new Audio();
audioOrder.src="/static/neworder.mp3";
var audioMsg=new Audio();
audioMsg.src="/static/newmsg.mp3";
//audioMsg.play();
function newOrder(){
	$.ajax({
		url:"/moduleshop.php?m=flk&a=notice&ajax=1",
		dataType:"json",
		success:function(res){
			if(res.neworder){
				if(localStorage.getItem("noticeMp3")==1){
					audioOrder.play();
				}
				
			}
			if(res.newMsg){
				setTimeout(function(){
					if(localStorage.getItem("noticeMp3")==1){
						audioMsg.play();
					}
				},3000);
				
			}
		}
	})
}