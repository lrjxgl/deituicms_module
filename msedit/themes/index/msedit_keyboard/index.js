var App=new Vue({
	el:"#App",
	data:function(){
		return {
			orderSounds:[],
			piano:Object,
			key_stime:0,
			key_etime:0,
			playSound:Object,
			keyWidth:30,
			bkeyWidth:20,
			keyScrollLeft:570,
			isRecord:false,
			playkeyStart:12,
			playKeys:[],
			isMobile:false
		}
	},
	created:function(){
		var that=this;
		this.orderSounds=orderSounds;
		this.playKeys=playKeys;
		this.piano=new soundBase({'sound':'AcousticGrand','tempo':90},100);
		this.$nextTick(function(){
			document.getElementById("pianoBox").scrollLeft=that.keyScrollLeft;
		})
		if(typeof(plus)!="undefined"){
			plus.screen.lockOrientation( 'landscape-primary');
		}
		if(!isMobile()){
			
			var o=[];
			var m=this.playkeyStart+playKeys.length;
			for(var i=this.playkeyStart;i<m;i++){
				o.push(orderSounds[i]);
			}
			this.orderSounds=o;
			$(window).on("keydown",function(e){
				console.log(e.key);
				var index=playKeys.indexOf(e.key);
				if(index>0){
					index+=that.playkeyStart;
				}
				console.log(index,orderSounds[index])
				that.piano.playSound(orderSounds[index],1000,1)
			})
		}else{
			this.isMobile=true;
		}
	},
	methods:{
		blackLeft:function(index){
			var m=0;
			for(var i in this.orderSounds){
				if(i==index){
					return m*this.keyWidth-this.bkeyWidth/2
				}
				if(this.orderSounds[i].indexOf("b")<0){
					m++
				}
				
			}
			console.log(m)
		},
		record:function(){
			if(this.isRecord==false){
				this.isRecord=true;
				mediaRecorder.start();
				
			}else{
				mediaRecorder.stop();
				this.isRecord=false;
			}
			
		},
		keyLeft:function(){
			var that=this;
			this.keyScrollLeft-=300;
			this.$nextTick(function(){
				document.getElementById("pianoBox").scrollLeft=that.keyScrollLeft;
			})
			
		},
		keyRight:function(){
			var that=this;
			this.keyScrollLeft+=300;
	 
			this.$nextTick(function(){
				console.log(document.getElementById("pianoBox"))
				document.getElementById("pianoBox").scrollLeft=that.keyScrollLeft;
			})
		},
		keySmall:function(){
			this.keyWidth-=5;
			this.bkeyWidth-=5;
		},
		keyLarge:function(){
			this.keyWidth+=5;
			this.bkeyWidth+=5;
		},
		mousedown:function(s){
			if(isMobile()){
				return false;
			}
			this.playOne(s)
		},
		mouseup:function(){
			if(isMobile()){
				return false;
			}
			this.playStop();
		},
		touchstart:function(s){
			this.playOne(s)
		},
		touchend:function(){
			this.playStop();
		},
		 
		playOne:function(s){
			console.log("mousedown")
			this.key_stime=new Date().getTime();
			this.playSound=this.piano.playOne(s)
		},
		playStop:function(){
			var that=this;
			this.key_etime=new Date().getTime();
			
			var ms=1000+(this.key_etime-this.key_stime)*10;
			console.log(ms)
			setTimeout(function(){
				that.playSound.stop();
			},ms)
		}
		
	}
})