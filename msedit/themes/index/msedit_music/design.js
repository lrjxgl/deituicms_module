 
 
 
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
			orderSounds:[],
			guSounds:[],
			startTime:0,
			piano:Object,
			gu:Object,
			isRecord:false,
			isSing:false,
			showKey:false,
			showGu:false,
			showSound:false,
			sound:"ElectricPiano",
			soundList:["acoustic_guitar_steel","AcousticGrand","ElectricPiano","Harp","MusicBox","violin"],
			rules:[],
			eTab:"all",
			eRule:-1,
			emusic:""
		}
	},
	created:function(){
		this.id=id;
		this.orderSounds=orderSounds;
		this.guSounds=guSounds;
		this.getPage();
		this.piano=new soundBase({'sound':'ElectricPiano','tempo':90},100);
		this.gu=new soundBase({'sound':'gu','type':'gu','tempo':90},100);
	},
	
	methods:{
		setSound:function(s){
			this.sound=s;
			this.piano=new soundBase({'sound':s,'tempo':90},100);
		},
		playOne:function(s){
			this.piano.playSound(s,1000,1)
		},
		playGu:function(s){
			this.gu.playGuSound(s,1000,1)
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url: "/module.php?m=msedit_music&a=design&ajax=1&id=" + id,
				dataType: "json",
				success: function(res) {
					that.data=res.data.data;
					that.$nextTick(function(){
						tabIndent.renderAll();
					}) 
					that.initMusic();
				}
			})
		},
		editRule:function(index){
			this.eRule=index;
			this.eTab='rule';
			this.emusic=this.ureplaceN(this.rules[index].music);
		},
		saveRule:function(){
			
			this.rules[this.eRule].music=this.replaceN(this.emusic);
			musicSound.rules=this.rules;
			this.data.content=this.prettyFormat(JSON.stringify(musicSound));
			this.eTab="all";
			this.eRule=-1;
		},
		replaceN:function(str){
			str=str.replace(/[\n\r]/g,"                                                                                                                                                                                                                                                ")
			.replace(/[\t]/g,"                       ")
			return str;
		},
		ureplaceN:function(str){
			str=str.replace(/                                                                                                                                                                                                                                                /g,"\n\r")
			.replace(/                       /g,"\t")
			return str;
		},
		prettyFormat:function (str) {
		  try {
		    // 设置缩进为2个空格
		    str = JSON.stringify(JSON.parse(str), null, 2);
		    str = str
		      .replace(/&/g, '&amp;')
		      .replace(/</g, '&lt;')
		      .replace(/>/g, '&gt;');
		    return str.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		      var cls = 'number';
		      if (/^"/.test(match)) {
		        if (/:$/.test(match)) {
		          cls = 'key';
		        } else {
		          cls = 'string';
		        }
		      } else if (/true|false/.test(match)) {
		        cls = 'boolean';
		      } else if (/null/.test(match)) {
		        cls = 'null';
		      }
		      return match;
		    });
		  } catch (e) {
		    alert("异常信息:" + e);
		  }
		}
		,
		initMusic:function(){
			musicSound = JSON.parse(this.replaceN(this.data.content));
			this.rules=musicSound.rules
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
		stop:function(){
			this.closePlayers();
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
					//解析时长
					var index=0;
					if(that.startTime>0){
						index=players[i].getMusicIndexByTime(that.startTime,ms.music);
						console.log("开始播放",index)
					}
					if (ms.type == 'single') {
						players[i].playOrder(ms.music, index, ms.volume)
					} else if (ms.type == 'group') {
						players[i].playChord(ms.music, index, ms.volume)
					} else if (ms.type == 'gu') {
						players[i].playGu(ms.music, index, ms.volume)
					}else if(ms.type=='loop'){
						players[i].playGu(ms.music, index, ms.volume)
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
		},
		save:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=msedit_music&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					id:this.data.id,
					content:this.data.content
				},
				success:function(res){
					skyToast(res.message)
				}
			})
		}
	}
})