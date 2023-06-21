var GPS={
	expire:600,
	set:function(v){
		v.expire=Date.parse(new Date())/1000+this.expire;
		var str=JSON.stringify(v);
		localStorage.setItem("gps",str);
	},
	get:function(){
		var v=localStorage.getItem("gps");
		var json=JSON.parse(v);
		if(!json){
			return false;
		}
		var time=Date.parse(new Date())/1000;
		if(json.expire<time){
			return false;
		}else{
			return json;
		}
	}
}

function newNotice(){
	newOrder();
	setTimeout(function(){
		newNotice()
	},60000)
}
newNotice()

var audioOrder=new Audio();
audioOrder.src="/static/neworder.mp3";
var audioMsg=new Audio();
audioMsg.src="/static/newmsg.mp3";
//audioMsg.play();
function newOrder(){
	$.ajax({
		url:"/moduleshop.php?m=gread&a=notice&ajax=1",
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