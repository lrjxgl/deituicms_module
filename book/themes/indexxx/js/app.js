function skyToast(msg){
	var html='<div id="toast" class="toast toast-success">'+msg+'</div>';
	if($("#toast").length>0){
		$("#toast").html(msg).show();
		
	}else{
		$("body").append(html);
	}
	setTimeout(function(){
		$("#toast").hide();
	},1000)
}
function goBack(goindex){
	if(typeof(goindex)=="undefined") goindex="-1";
	if (document.referrer != null && document.referrer != "") {
          window.history.go(goindex);
     } else{
			window.location="/";
	}
}

function getCheckIn(){
	$.get("/index.php?m=checkin&a=get&ajax=1",function(data){
		if(data.data.days>0){
			$("#LAY_signin_days").html("你已经连续签到了"+data.data.days+"天");
		}
		if(data.data.ischecked){
			$("#LAY_signin").html("今日已签到").addClass("layui-btn-disabled");
			$("#LAY_signin_vd").html("获得<cite>"+data.data.gold+"</cite>个金币");
		}else{
			$("#LAY_signin_vd").html("可获得<cite>"+data.data.gold+"</cite>个金币");
		}
		
	},"json")
}
getCheckIn();
$(document).on("click",".js-submit",function(){
	var p=$(this).parents("form");
	var url=p.attr("action");
	$.post(url,p.serialize(),function(data){
		layer.msg(data.message);
		if(data.error==0){
			if(p.attr("ungo")!=1){
				setTimeout(function(){
					goBack();
				},300);
			}
			
		}
		
	},"json")
})

$(document).on("click",".goBack",function(){
	goBack();		
});
$(document).on("click","#LAY_signin",function(){
	$.post("/index.php?m=checkin&a=save&ajax=1",function(data){
		layer.msg(data.message);
	},"json")
})
$(document).on("click","#LAY_signinHelp",function(){
	layer.open({
      type: 1
      ,title: '签到说明'
      ,area: '300px'
      ,shade: 0.8
      ,shadeClose: true
      ,content: ['<div class="layui-text" style="padding: 20px;">'
        ,'<blockquote class="layui-elem-quote">“签到”可获得金币，规则如下</blockquote>'
        ,'<table class="layui-table">'
          ,'<thead>'
            ,'<tr><th>连续签到天数</th><th>每天可获金币</th></tr>'
          ,'</thead>'
          ,'<tbody>'
            ,'<tr><td>1</td><td>1</td></tr>'
            ,'<tr><td><64</td><td>1+天数</td></tr>'
            ,'<tr><td>≥64</td><td>64</td></tr>'
             
          ,'</tbody>'
        ,'</table>'
        ,'<ul>'
          ,'<li>中间若有间隔，则连续天数重新计算</li>'
          ,'<li style="color: #FF5722;">不可利用程序自动签到，否则金币清零</li>'
        ,'</ul>'
      ,'</div>'].join('')
    });
})
