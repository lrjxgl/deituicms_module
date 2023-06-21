var app=new Vue({
	el:"#App",
	data:function(){
		return {
			piano:Object,
			paiNum:1,
			meter:"4/4",
			tempo:90,
			meterList:[
				"2/2","3/4","4/4","8/8"
			]
		}
	},
	created:function(){
		this.piano=new soundBase({'sound':'gu'},this.tempo);
		
	},
	watch:{
		tempo:function(n,o){
			this.closePlayers();
			this.piano=new soundBase({'sound':'gu'},this.tempo);
		}
		
	},
	methods:{
		closePlayers:function() {
			for (var j in this.piano.aus) {
				if(this.piano.aus[j].currentTime > 0){
					this.piano.aus[j].currentTime = 0;
				}
			}
			 
			for (var j in this.piano.timerout) {
				clearTimeout(this.piano.timerout[j])
					
			}
			this.piano.timerout = []; 
			
		},
		play:function(){
			var that=this;
			that.closePlayers();
			var music="kick 1,snare 1,snare 1,snare 1";
			switch(this.meter){
				case "2/2":
					music="kick 1,snare 1";
					break;
				case "3/4":
					music="kick 1,snare 1,snare 1";
					break;
				case "8/4":
					music="kick /,snare /,kick /,snare /,kick /,snare /,kick /,snare /";
					break;
			}
			
			this.piano.loop=true;
			that.playGu(music,0,1);
			
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
			var ms=that.piano.parseTimer(mss);
			that.paiNum=index+1;
			that.piano.playGuSound(yy,ms,volume);
			var it=setTimeout(function(){
				if(arr.length-1==index){
					index=-1;				
				}
				that.playGu(guStr,index+1,volume);
			},ms)
			that.piano.timerout.push(it)
			
			
		}
		
	}
})