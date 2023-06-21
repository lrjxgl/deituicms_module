var ws;
ws=new WebSocket(ws_host);
ws.onopen = function(evt) { 
  var msg = JSON.stringify({
  	type: "login", 	 
  	gid: ws_gid,
	k: ws_uid,
  	content: "login"
  });
  ws.send(msg)
};

ws.onmessage = function(evt) {
	var res=JSON.parse(evt.data);
	switch(res.type){
		case "say":
			 
			msApp.msgList.push({
				nickname:res.nickname,
				user_head:res.user_head,
				content:res.content,
				time:res.time
			});
			msApp.$nextTick(() => {
				$(".msgList").scrollTop(10000);
			 })
			break;
		default:
			console.log(  evt.data);
			break;
	}
  
   
};

ws.onclose = function(evt) {
  console.log("Connection closed.");
}; 
function ws_send(content){
	var msg = JSON.stringify({
		type: "say",
		gid: ws_gid,
		nickname:ssuser.nickname,
		user_head:ssuser.user_head,
		content: content
	});
	ws.send(msg);
}   