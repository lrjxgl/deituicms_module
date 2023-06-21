var App = new Vue({
	el: "#App",
	data: function() {
		return {
			list: [],
			myList: [],
			tab: "all",
			addr: {},
			in_orderid: 0,
			isAddr: false,
			product:{}
		}
	},
	created: function() {
		this.getOrder();
		this.getMy();
		this.getAddr();
	},
	methods: {
		setTab: function(tab) {

			if (tab == 'all') {
				this.getOrder();
			} else {
				this.getMy();
			}
			this.tab = tab;
		},
		getOrder: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=dzp_event&a=order&ajax=1",
				data: {
					eventid: eventid
				},
				dataType: "json",
				success: function(res) {
					that.list = res.data.list;
				}
			})
		},
		getAddr: function() {
			var that = this;
			$.ajax({
				url: "/index.php?m=user_lastaddr&a=my&ajax=1",
				dataType: "json",
				success: function(res) {
					if (res.error) {
						return false;
					}
					that.addr = res.data;
				}
			})
		},
		showAddr: function(orderid) {
			this.in_orderid = orderid;
			this.isAddr = true;
		},
		hideAddr: function() {
			this.in_orderid = 0;
			this.isAddr = false;
		},
		getMy: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=dzp_event&a=myorder&ajax=1",
				data: {
					eventid: eventid
				},
				dataType: "json",
				success: function(res) {
					if (res.error) {
						return false;
					}
					that.myList = res.data.list;
				}
			})
		},
		changeAddr: function() {
			var that = this;
			skyJs.confirm({
				content: "请确保正常填写收货地址",
				success: function() {
					$.ajax({
						url: "/module.php?m=dzp_order&a=changeaddr&ajax=1",
						data: {
							orderid: that.in_orderid,
							nickname: that.addr.nickname,
							address: that.addr.address,
							telephone: that.addr.telephone
						},
						type: "POST",
						dataType: "json",
						success: function(res) {
							if (res.error) {
								return false;
							}
							that.hideAddr();
							that.getMy();
						}
					})
				}
			})

		},
		received: function(orderid) {
			var that = this;
			that.in_orderid = orderid;
			skyJs.confirm({
				content: "请确认已收到",
				success: function() {
					$.ajax({
						url: "/module.php?m=dzp_order&a=received&ajax=1",
						data: {
							orderid: that.in_orderid
						},
						dataType: "json",
						success: function(res) {
							if (res.error) {
								return false;
							}

							that.getMy();
						}
					})
				}
			})
		}
	}
})

/**画布***/
var canvasFinish=true;
var canvas = document.getElementById("canvas");
var context = canvas.getContext('2d');
function canvasInit(){
	context.globalCompositeOperation = "source-over";
	context.beginPath();
	context.fillStyle = 'grey'
	context.fillRect(0, 0, 320, 80);
	$("#canvas").css({
	 	opacity: 1
	});
}
canvasInit();
var oY=$("#canvas").offset().top;
var winW=$(window).innerWidth();
var oX=(winW-320)/2;
console.log(oY)
//鼠标按下开刮
canvas.addEventListener("touchstart", function(event) {
	canvas.addEventListener("touchmove", function(event) {
		if(canvasFinish){
			return false;
		}
		console.log(event)
		//获取鼠标坐标
		var x = event.touches[0].pageX-oX;
		var y = event.touches[0].pageY-oY;
		//destination-out    显示原来的不在后来区域的部分
		context.globalCompositeOperation = "destination-out";
		context.beginPath();
		context.arc(x, y, 5, 0, Math.PI * 2);
		context.fill();
		checkComplete();
	}, false);
}, false)
//鼠标抬起不刮开
canvas.addEventListener("touchend", function(event) {
	canvas.addEventListener("touchmove", function(event) {

	}, false);
}, false);
function checkComplete() {
	var imgData = context.getImageData(0, 0, 240, 65);
	var pxData = imgData.data;// 获取字节数据
	var len = pxData.length; // 获取字节长度
	var count = 0; // 记录透明点的个数
	// 主要的思想是 一个像素由四个数据组成，每个数据分别是 rgba() 所以第四个数据 a 表示alpha透明度
	for (var i = 0; i < len; i += 4) {
		var alpha = pxData[i + 3]; // 获取每个像素的透明度
		if (alpha < 10) {
			// 透明度小于10 
			count++;
		}
	}
	var percent = count / (len / 4);// 计算百分比
	 
	if (percent >= 0.2) { 
		$("#canvas").css({
			opacity: 0
		});
		if(App.$data.product.isorder){
			App.getOrder();
			App.getMy();
		}
		setTimeout(function(){
			canvasFinish=true;
			$('#getOrder').html("再来一次").show();
		},2000)
		
	}
}
// 抽取按钮按钮点击触发事件
$('#getOrder').on("click", function() {
	// 正在转动，直接返回
	canvasInit();
	$.ajax({
		url: "/module.php?m=dzp_event&a=getindex&ajax=1",
		data: {
			eventid: eventid
		},
		dataType: "json",
		success: function(res) {
			if (res.error) {
				skyJs.toast(res.message, "error");
				canvasFinish=true; 
				return false;
			}
			canvasFinish=false;
			var index = res.data.index;
			$("#ggkReward").html(res.data.product.reward_desc);
			App.$data.product=res.data.product;
			$("#ggkReward").css({
				opacity: 1
			}); 
			$('#getOrder').hide();
		}
	})

});
