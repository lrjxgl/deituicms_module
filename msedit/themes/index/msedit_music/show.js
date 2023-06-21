 
//处理乐谱
var players = [];
var musicSound;


var App=new Vue({
	el:"#App",
	data:function(){
		return {
			id:0,
			data:{},
			timer:0,
			isRecord:false,
			isSing:false, 
		}
	},
	created:function(){
		this.id=id;
		 
		this.getPage();
		 
	},
	methods:{
		 
		getPage:function(){
			var that=this;
			$.ajax({
				url: "/module.php?m=msedit_music&a=design&ajax=1&id=" + id,
				dataType: "json",
				success: function(res) {
					that.data=res.data.data;
 
					that.initMusic();
				}
			})
		},
		initMusic:function(){
			musicSound = JSON.parse(this.data.content.replaceAll(/[\n\r\t]/g,""));
			for (var i in musicSound.rules) {
				var ms = musicSound.rules[i];
				players[i] = new soundBase(ms, musicSound.tempo)
			
			}
		},
		closePlayers:function() {
			for (var i in players) {
				
				for (var j in players[i].aus) {
					if (players[i].aus[j].currentTime > 0) {
						players[i].aus[j].currentTime = 0;
					}
		
				}
				for (var j in players[i].timerout) {
					clearTimeout(players[i].timerout[j])
		
				}
				players[i].timerout = [];
			}
			
		},
		 
		replay:function(){
			var that=this;
			this.closePlayers();
			players = [];
			this.initMusic();
			var it=setInterval(function(){
				//检测是否全部可播放
				for (var i in players) {
					if(players[i].aus.length+players[i].gus.length==players[i].loadNum){
						 
						that.play();
						clearInterval(it)
					}else{
						console.log(players[i].loadNum);
					}				 
				}
			},100)
			
		},
		play:function(){
			var that=this;
			
			this.closePlayers();
			if(this.timer>0){
				clearTimeout(this.timer)
			}
			this.timer=setTimeout(function() {
				that.closePlayers()
			}, musicSound.time)
			if(this.isSing){
				var uRecord=mediaRecorder2;
			}else{
				var uRecord=mediaRecorder;
			}
			
			if(this.isRecord){
				if(uRecord.state=='recording'){
					uRecord.stop()
				}
				uRecord.start()
			}
			setTimeout(function(){
				for (var i in musicSound.rules) {
					var ms = musicSound.rules[i];
					console.log(ms.volume)
					if (ms.type == 'single') {
						players[i].playOrder(ms.music, 0, ms.volume)
					} else if (ms.type == 'group') {
						players[i].playChord(ms.music, 0, ms.volume)
					} else if (ms.type == 'gu') {
						players[i].playGu(ms.music, 0, ms.volume)
					}else if(ms.type=='loop'){
						players[i].playGu(ms.music, 0, ms.volume)
					}
				
				}
			},1000)
			
			if(this.isRecord){
				setTimeout(function(){
					uRecord.stop()
				},musicSound.time)
				
			}
		},
		stop:function(){
			if(this.isSing){
				var uRecord=mediaRecorder2;
			}else{
				var uRecord=mediaRecorder;
			}
			this.closePlayers()
			if(this.isRecord){
				
				uRecord.stop()
				
			}
		}
	}
})