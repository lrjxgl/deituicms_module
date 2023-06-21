var xuyuan = {}
 xuyuan.zindex = 1;
 xuyuan.width = 590;
 xuyuan.height = 390;
 xuyuan.ismove = false;
 xuyuan.lastX = 0;
 xuyuan.lastY = 0;

 function xy_init() {
 	console.log($(".xy-list").height());
 	xuyuan.width = $(window).width() - 60;
 	xuyuan.height = $(".xy-list").height() - 100;

 	$(".xy-item").each(function() {
 		var m = Math.random() * xuyuan.width;
 		var n = Math.random() * xuyuan.height;
 		$(this).css({
 			left: m,
 			top: n
 		});
 	});
 }

 $(window).bind("touchmove", function(event) {
 	event.preventDefault();
 });

 $(window).resize(function() {
 	xy_init();
 })
 $(document).on("click", ".js-mp3", function() {
 	console.log("mp3click");
 	$("#mp3").attr("src", "/module.php?m=tts&text=" + encodeURI($(this).attr("v")))
 	document.querySelector("#mp3").play();
 })
 $(document).on("mousedown", ".xy-item", function(e) {
 	xuyuan.zindex++;
 	xuyuan.ismove = true;
 	xuyuan.lastX = e.clientX;
 	xuyuan.lastY = e.clientY;
 	$(this).css("z-index", xuyuan.zindex)
 })

 $(document).on("mousemove", ".xy-item", function(e) {
 	if (xuyuan.ismove) {

 		var m = parseInt($(this).css("left")) + e.clientX - xuyuan.lastX;
 		var n = parseInt($(this).css("top")) + e.clientY - xuyuan.lastY;
 		console.log(m);
 		//m=Math.min(m,xuyuan.width);
 		//n=Math.min(m,xuyuan.height);
 		$(this).css({
 			left: m,
 			top: n
 		});
 		xuyuan.lastX = e.clientX;
 		xuyuan.lastY = e.clientY;
 	}
 })
 $(document).on("touchstart", ".xy-item", function(e) {
 	var touch = e.originalEvent.touches[0];
 	console.log(e);
 	xuyuan.zindex++;
 	xuyuan.ismove = true;
 	xuyuan.lastX = touch.pageX;
 	xuyuan.lastY = touch.pageY;
 	$(this).css("z-index", xuyuan.zindex)
 })
 $(document).on("touchmove", ".xy-item", function(event) {
 	if (xuyuan.ismove) {

 		if (event.originalEvent.touches.length == 1) {
 			event.preventDefault(); // 阻止浏览器默认事件，重要 
 			var touch = event.originalEvent.touches[0];

 			var m = parseInt($(this).css("left")) + touch.pageX - xuyuan.lastX;
 			var n = parseInt($(this).css("top")) + touch.pageY - xuyuan.lastY;
 			$(this).css({
 				left: m,
 				top: n
 			});
 			xuyuan.lastX = touch.pageX;
 			xuyuan.lastY = touch.pageY;
 		}
 	}
 })
 $(document).on("touchend", ".xy-item", function(e) {
 	xuyuan.ismove = false;
 })
 $(document).on("mouseup", ".xy-item", function(e) {
 	xuyuan.ismove = false;
 })
 $(document).on("mouseleave", ".xy-item", function(e) {
 	xuyuan.ismove = false;
 })
 $(document).on("focusout", ".xy-item", function(e) {
 	xuyuan.ismove = false;
 });


 $(document).on("click", "#xuyuan-add", function() {
 	console.log("add")
 	App.$data.addBox = true;
 	console.log(App.$data.addBox)
 });




 App = new Vue({
 	el: "#app",
 	data: function() {
 		return {
 			pageLoad: false,
 			per_page: 0,
 			isFirst: true,
 			list: [],
 			keyword: "",
 			searchBox: false,
 			addBox: false
 		}
 	},
 	created: function() {
 		this.getPage();
 	},
 	methods: {
 		getPage: function() {
 			var that = this;
 			$.ajax({
 				url: "/module.php?m=xuyuan&a=list&ajax=1",
 				dataType: "json",
 				data: {
 					keyword: this.keyword
 				},
 				success: function(res) {
					
 					that.list = res.data.list;
					 
 					that.per_page = res.data.per_page;
 					that.$nextTick(function(){
						xy_init();
					})
					
 				}
 			})
 		},
 		getNext: function() {
 			var that = this;
			if(this.per_page==0){
				skyToast("没用更多许愿了")
				return false;
			}
 			$.ajax({
 				url: "/module.php?m=xuyuan&a=list&ajax=1",
 				dataType: "json",
 				data: {
 					per_page: this.per_page,
 					keyword: this.keyword
 				},
 				success: function(res) {
 					that.list = res.data.list;
 					that.per_page = res.data.per_page;
 					that.$nextTick(function(){
 						xy_init();
 					})
 				}
 			})
 		},
 		search: function() {
			this.searchBox=false;
 			this.getPage();
 		},
		unSearch:function(){
			console.log("unsearch")
			this.keyword="";
			this.searchBox=false;
			
			this.getPage()
		},
		del:function(item){
			var list=[];
			for(var i in that.list){
				if(that.list[i].id!=item.id){
					list.push(that.list[i]);
				}
			}
			that.list=list;
		},
 		submit: function() {
 			var that = this;
 			$.ajax({
 				url: "/module.php?m=xuyuan&a=save&ajax=1",
 				dataType: "json",
 				type: "POST",
 				data: $('#addForm').serialize(),
 				success: function(res) {
 					if (res.error == 0) {
 						skyToast('许愿成功', 'success');

 						that.getPage();
 						that.addBox = false;
 					} else {
 						skyToast(res.message);
 					}
 				}
 			})

 		}
 	}
 })
