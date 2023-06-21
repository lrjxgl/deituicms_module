function skyToast(msg) {
	var html = '<div id="toast" class="toast toast-success">' + msg + '</div>';
	if ($("#toast").length > 0) {
		$("#toast").html(msg).show();

	} else {
		$("body").append(html);
	}
	setTimeout(function() {
		$("#toast").hide();
	}, 1000)
}

function smallImg(s) {
	return s + ".100x100.jpg";
}

function reply_text(nick, con) {
	return "回复@" + nick + ":" + con;
}
var cm = new Vue({
	el: '#page-comment-list',
	data: function() {
		return {
			pageLoad: false,
			data: []
		}

	},
	created: function() {

	}

})
var bjApp = new Vue({
	el: '#bjList',
	data: function() {
		return {
			pageLoad: false,
			list: []
		}

	},
	created: function() {
		this.getPage();
	},
	methods: {
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=book_note&a=article&ajax=1",
				data: {
					articleid: articleid
				},
				dataType: "json",
				success: function(res) {
					that.list = res.data.list;
				}
			})
		}
	}
})

$(document).on("click", ".look-article", function() {
	$(".d-content").toggleClass("none");
})
$(document).on("click", ".look-bj", function() {
	$("#bjlist-modal").show();
})
$(document).on("click", "#bj-btn", function() {
	$("#bj-modal").show();
})
$(document).on("click", "#bj-close", function() {
	$("#bj-modal").hide();
})
$(document).on("click", "#wSubmit", function() {
	$.post("/module.php?m=book_note&a=save&ajax=1", {
		content: $("#wCon").val(),
		bookid: bookid,
		articleid: articleid
	}, function(res) {
		skyToast(res.message);
		$("#bj-modal").hide();
		bjApp.getPage();
	}, "json")
})
SyntaxHighlighter.highlight();
setTimeout(function() {
	$.get("/module.php?m=book_article&a=addClick&id={$data.id}")
}, 3000)

$(function(){
	var comment_insubmit=false;
 
	var comment_pid=0
	getList();
	function getList(){
  		$.get('/module.php?m=book_comment&ajax=1',{
  			articleid:articleid
  		},function(res){
			cm.pageLoad=true;
  			cm.data=res.data.data;
			$('#page-comment-list').show();
  		},"json")
  	}
	$(document).on("click","#comment-btn",function(e){
		$("#commentbox").show();
	}); 
	$(document).on("click","#comment-cancel",function(e){
		$("#comment-content").val("");
		$("#commentbox").hide();
		comment_pid=0;
	});
	$(document).on("click","#comment-submit",function(){
		if(comment_insubmit) return false;
		comment_insubmit=true;
		setTimeout(function(){ comment_insubmit=false; },1000);
		 
		var pdata={
				content:$("#comment-content").val(),
				bookid:bookid,
				articleid:articleid,
				pid:comment_pid
			}
		$.post("/module.php?m=book_comment&a=save&ajax=1",pdata,function(data){
			if(data.error==0){
				$("#comment-content").val("");
				$("#commentbox").hide();
				comment_pid=0;
				getList();
				skyToast(data.message);
			}else{
				
				if(data.nologin !=undefined){
					skyToast('请先登录');
				}else{
					
					skyToast(data.message);
				}
			}
		},"json")
	})
	
	$(document).on("click",".comment_reply_btn",function(){
		$("#commentbox").show();
		
		comment_pid=$(this).attr("pid");
		$("#comment-content").focus().val($(this).attr("reply_text") + " ");
	});


});
