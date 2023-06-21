let context = new (window.AudioContext || window.webkitAudioContext)();
let dest = context.createMediaStreamDestination();
let pitches1 = [
	"A1", "A2", "A3", "A4", "A5", "A6", "A7",
	"B1", "B2", "B3", "B4", "B5", "B6", "B7",
	"C1", "C2", "C3", "C4", "C5", "C6", "C7", "C8",
	"D1", "D2", "D3", "D4", "D5", "D6", "D7",
	"E1", "E2", "E3", "E4", "E5", "E6", "E7",
	"F1", "F2", "F3", "F4", "F5", "F6", "F7",
	"G1", "G2", "G3", "G4", "G5", "G6", "G7",
	"Bb1", "Bb2", "Bb3", "Bb4", "Bb5", "Bb6", "Bb7",
	"Db1", "Db2", "Db3", "Db4", "Db5", "Db6", "Db7",
	"Eb1", "Eb2", "Eb3", "Eb4", "Eb5", "Eb6", "Eb7",
	"Ab1", "Ab2", "Ab3", "Ab4", "Ab5", "Ab6", "Ab7",
	"Gb1", "Gb2", "Gb3", "Gb4", "Gb5", "Gb6", "Gb7",
];
let pitches2 = [
	"..6", ".6", "6", "6.", "6..", "6.3", "6.4",
	"..7", ".7", "7", "7.", "7..", "7.3", "7.4",
	"..1", ".1", "1", "1.", "1..", "1.3", "1.4","1.5",
	"..2", ".2", "2", "2.", "2..", "2.3", "2.4",
	"..3", ".3", "3", "3.", "3..", "3.3", "3.4",
	"..4", ".4", "4", "4.", "4..", "4.3", "4.4",
	"..5", ".5", "5", "5.", "5..", "5.3", "5.4",
	"..b7", ".b7", "b7", "b7.", "b7..", "b7.3", "b7.4",
	"..b2", ".b2", "b2", "b2.", "b2..", "b2.3", "b2.4",
	"..b3", ".b3", "b3", "b3.", "b3..", "b3.3", "b3.4",
	"..b6", ".b6", "b6", "b6.", "b6..", "b6.3", "b6.4",
	"..b5", ".b5", "b5", "b5.", "b5..", "b5.3", "b5.4",
];
let guSounds=[
	"closedhat","crash","kick","openhat","snare"
];
let orderSounds=[
	"C1","Db1","D1","Eb1","E1","F1","Gb1","G1","Ab1","A1","Bb1","B1",
	"C2","Db2","D2","Eb2","E2","F2","Gb2","G2","Ab2","A2","Bb2","B2",
	"C3","Db3","D3","Eb3","E3","F3","Gb3","G3","Ab3","A3","Bb3","B3",
	"C4","Db4","D4","Eb4","E4","F4","Gb4","G4","Ab4","A4","Bb4","B4",
	"C5","Db5","D5","Eb5","E5","F5","Gb5","G5","Ab5","A5","Bb5","B5",
	"C6","Db6","D6","Eb6","E6","F6","Gb6","G6","Ab6","A6","Bb6","B6",
	"C7","Db7","D7","Eb7","E7","F7","Gb7","G7","Ab7","A7","Bb7","B7",
	"C8"
];
let playKeys=[
	"z","x","c","v","b","n","m",",",".","/","shift","up",
	"a","s","d","f","g","h","j","k","l",";","'","enter",
	"q","w","e","r","t","y","u","i","o","p","[","]",
	"1","2","3","4","5","6","7","8","9","0","-","="
]
let chordSounds={
	"C":"C2,E2,G2",
	"Dm":"D2,F2,A2",
	"Em":"E2,G2,B2",
	"F":"F2,A2,C3",
	"G":"G2,B2,D3",
	"Am":"A2,C3,E3",
	"Bm":"B2,D3,Gb3"
};
function soundBase(music,tempo){
	var pitches;
	//加载音乐
	var aus = [];
	var gus=[];
	pitches=pitches1;
	if(music.sound=='gu'){
		for (let i in guSounds) {
		    let request = new XMLHttpRequest();
		    request.open("GET", "/module/msedit/themes/index/audio/" + music.sound + "/" + guSounds[i] + ".mp3");
		    request.responseType = "arraybuffer";
		    request.onload = function() {
				obj.loadNum++
		        let undecodedAudio = request.response;
		        context.decodeAudioData(undecodedAudio, (data) => gus[i] = data)
		    }
		    request.send();
		}
	}else{
		for (let i in pitches) {
		    let request = new XMLHttpRequest();
		    request.open("GET", "/module/msedit/themes/index/audio/" + music.sound + "/" + pitches[i] + ".mp3");
		    request.responseType = "arraybuffer";
		    request.onload = function() {
				obj.loadNum++
		        let undecodedAudio = request.response;
		        context.decodeAudioData(undecodedAudio, (data) => aus[i] = data)
		    }
		    request.send();
		}
	}
	if(music.score=='jianpu'){
		pitches=pitches2;
	} 
	
	var loop=false;
	if(typeof(music.loop)!="undefined"){
		loop=music.loop;
	}
	 
	var obj={
		aus:aus,
		gus:gus,
		loop:loop,
		loadNum:0,
		timerout:[],
		timer:0, 
		parseTimer:function(t){
			switch(t.trim()){
				case "/":
					return this.timer/2;
					break;
				case "/.":
					return this.timer/2+this.timer/4;
					break;
				case "//":
					return this.timer/4;
					break;
				case "///":	
				case "/3":
					return this.timer/8;
					break;
				case "2":	
				case "-":
					return this.timer*2;
					break;
				case "4":
				case "--":
					return this.timer*4;
					break;	
				case ".":
					return this.timer*3/2;
					break;
				case "..":
					return this.timer*3/2+this.timer/4;
					break;
				
				case "1":
					return this.timer;	
				 
				case "3":
					return this.timer*3;
					break;
				
				default:
					return this.timer;
			}
		},
		getMusicIndexByTime:function(time,str){
			var that=this;
			var arr=str.split(",");
			var ftime=0;
			 
			for(var index in arr){
				
				var s=arr[index]
				 
				var earr=s.trim().split(" ");
				//音高 
				var yy=earr[0].trim();	 
				//音长
				var mss;
				if(typeof(earr[1])!="undefined"){
					mss=earr[1];
				}else{
					mss="";
				}
				var ms=this.parseTimer(mss);
			 
				ftime+=ms;
				if(ftime/1000>=time){
					return parseInt(index);
				}
			}
		 
			return 0;
			
		},
		playOne:function(pitch){
			return this.playSound(pitch,10000,1);
		},
		playSound:function(pitch,timer,volume) {
			let playSound = context.createBufferSource();
			let gainNode = context.createGain();
			
			playSound.buffer = this.aus[pitches.indexOf(pitch)];  
			 
			gainNode.gain.value = volume
					
			playSound.connect(gainNode);
			gainNode.connect(dest);
			gainNode.connect(context.destination);
			playSound.start();
			setTimeout(function(){
				playSound.stop();
			},timer)
			return playSound;
		},
		
		playGroup:function(str, timer,volume) {
			var arr=str.split(",")
			for (var i in arr) {
				this.playSound(arr[i], timer,volume);
			}
		},
		playChord:function(chordStr,index,volume){
			var that=this;
			var chords=chordStr.trim().split(",")
			
			var _arr=chords[index].split(" ");
			var s=chordSounds[_arr[0]];
			var t=_arr[1];
			console.log(s,chords)
			var ms=this.parseTimer(t);
			 
			this.playGroup(s,ms,volume)
			var it=setTimeout(function(){
				if(chords.length-1==index){
					return false;
				}
				that.playChord(chordStr,index+1,volume)
			},ms)
			that.timerout.push(it)
		},
		playOrder:function(str,index,volume){
			
			var that=this;
			var arr=str.split(",");
			var s=arr[index]
			var earr=s.trim().split(" ");
			//音高 
			var yy=earr[0].trim();
			 
			 
			//音长
			var mss;
			if(typeof(earr[1])!="undefined"){
				mss=earr[1];
			}else{
				mss="";
			}
			var ms=this.parseTimer(mss);
			if(yy=="_"){
				var it=setTimeout(function(){
					if(arr.length-1==index){
						return false;
					}
					that.playOrder(str,index+1,volume);
				},ms)
			}else{
				that.playSound(yy,ms,volume);
				var it=setTimeout(function(){
					if(arr.length-1==index){
						return false;
					}
					that.playOrder(str,index+1,volume);
				},ms)
			}
			
			that.timerout.push(it)
			 
		},
		playGuSound:function(pitch,timer,volume) {
			let playSound = context.createBufferSource();
			let gainNode = context.createGain();
			
			playSound.buffer = this.gus[guSounds.indexOf(pitch)]; 
			gainNode.gain.value = volume
			gainNode.connect(dest);
			gainNode.connect(context.destination);
			playSound.connect(gainNode);
			playSound.start(timer/1000);
			
		},
	 
		playGu:function(guStr,index,volume){
			var that=this;
			var arr=guStr.split(",");
			//音长
			var _arr=arr[index].trim().split(" ");
			var t=_arr[1];
			var yy=_arr[0].trim();
			if(typeof(_arr[1])!="undefined"){
				mss=_arr[1];
			}else{
				mss="";
			}
			var ms=this.parseTimer(mss);
			if(yy=="_"){
				var it=setTimeout(function(){
					 
					if(arr.length-1==index){
						if(that.loop){
							index=-1;
						}else{
							return false;
						}
					}
					that.playGu(guStr,index+1,volume);
				},ms)
			}else{
				this.playGuSound(yy,ms,volume);
				var it=setTimeout(function(){
					if(arr.length-1==index){
						if(that.loop){
							index=-1;
						}else{
							return false;
						}					
					}
					that.playGu(guStr,index+1,volume);
				},ms)
				that.timerout.push(it)
			}
			
		}
	}
	 
	obj.timer=60*1000/tempo;
	console.log(obj.timer)
	return obj;
}