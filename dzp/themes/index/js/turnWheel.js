var turnWheel = {
	rewards: [],
	colors: [
		"#AE3EFF",
		"#4D3FFF",
		"#FC262C",
		"#3A8BFF",
		"#EE7602",
		"#FE339F",
		"#4D3FFF",
		"#FC262C"
	],
	outsideRadius: 160, //转盘外圆的半径
	textRadius: 200, //转盘奖品位置距离圆心的距离
	insideRadius: 68, //转盘内圆的半径
	startAngle:270/Math.PI , //开始角度
	bRotate: false, //false:停止;ture:旋转
	rotateAg: 5,
	rotateLimit: 20,
	timer: 0,
	goTime: 1000,
	canvas: false,
	ctx: false,
	init: function() {
		this.canvas = document.getElementById("wheel-canvas");
		this.ctx = this.canvas.getContext("2d");
	},
	draw: function() {
		var baseAngle = Math.PI * 2 / (turnWheel.rewards.length);

		var canvasW = this.canvas.width; // 画板的高度
		var canvasH = this.canvas.height; // 画板的宽度
		this.ctx.fillStyle = "#fff000";
		this.ctx.clearRect(0, 0, canvasW, canvasH); //去掉背景默认的黑色
		this.ctx.strokeStyle = "#199301"; //线的颜色
		this.ctx.font = '14px Microsoft YaHei';
		for (var index = 0; index < turnWheel.rewards.length; index++) {
			var angle = turnWheel.startAngle + index * baseAngle;
			this.ctx.fillStyle = turnWheel.colors[index];
			this.ctx.beginPath();
			this.ctx.arc(canvasW * 0.5, canvasH * 0.5, turnWheel.outsideRadius, angle, angle + baseAngle, false);
			this.ctx.arc(canvasW * 0.5, canvasH * 0.5, turnWheel.insideRadius, angle + baseAngle, angle, true);
			this.ctx.stroke();
			this.ctx.fill();
			this.ctx.save();
			this.ctx.fillStyle = "#FFFF00";
			var rewardName = turnWheel.rewards[index].title;

			var line_height = 24;
			var translateX = canvasW * 0.5 + Math.cos(angle + baseAngle / 2) * turnWheel.textRadius;
			var translateY = canvasH * 0.5 + Math.sin(angle + baseAngle / 2) * turnWheel.textRadius;
			this.ctx.translate(translateX, translateY);
			this.ctx.rotate(angle + baseAngle / 2 + Math.PI / 2);
			this.ctx.fillText(rewardName, -this.ctx.measureText(rewardName).width / 2, 100);
			this.ctx.restore();
		}
	},
	goRoate: function(index, callback) {
		var ag = 0;
		var radius = 360 / turnWheel.rotateAg * turnWheel.rotateLimit
		turnWheel.goTime = radius + radius * index / turnWheel.rewards.length;
		
		turnWheel.timer = setInterval(function() {
			ag += turnWheel.rotateAg;
			if (ag > 360) {
				ag = 0;
			}
			//var deg=ag*Math.PI/180;
			turnWheel.canvas.style.transform = 'rotate(' + ag + 'deg)';
			
		}, turnWheel.rotateLimit)
		
		setTimeout(function() {
			clearInterval(turnWheel.timer);
			turnWheel.bRotate = false;
			//防止在不对的停下
			ag=(360-360/turnWheel.rewards.length * index);
			 
			turnWheel.canvas.style.transform = 'rotate(-' + ag + 'deg)';
			callback(index);
		}, turnWheel.goTime)
	}
};
/*

turnWheel.rewards = [{
		title: "A",
		description: "中A奖"
	},
	{
		title: "B",
		description: "中B奖"
	},
	{
		title: "C",
		description: "中C奖"
	},
	{
		title: "D",
		description: "中D奖"
	},
	{
		title: "E",
		description: "中E奖"
	},
	{
		title: "F",
		description: "中F奖"
	}
];
turnWheel.init();
turnWheel.draw();


// 抽取按钮按钮点击触发事件
$('.wheel-pointer').click(function() {
	// 正在转动，直接返回
	if (turnWheel.bRotate) return;
	turnWheel.bRotate = true;
	var index = parseInt(Math.random() * 6);
	console.log(index);
	turnWheel.goRoate(6 - index, function() {
		console.log("开奖结果:", turnWheel.rewards[index].title)
	});
});
*/
