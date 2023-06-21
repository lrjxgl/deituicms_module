var app=new Vue({
	el:"#App",
	data:function(){
		return {
			data:[],
			id:0, 
			msgList:[],
			msgList2:[],
			content:"",
			timer:0,
			inpost:0,
			inpost_timer:0,
			inpost_time:30,
			conHeight:"40px",
			showPrompt:false,
			tsList:[],
			logList:[],
			showLog:false,
			true_oimg:"",
			chatimg_first:true
			 
		}
	},
	created:function(){
		this.id=id;
		var that=this;
		this.getPage();
		if(this.id>0){
			this.getMsg();
		}
		this.getPrompt();
	},
	methods:{
		newSearch:function(){
			this.id=0;
			this.content="/search ";
			this.msgList=[];
			this.msgList2=[];
		},
		getPrompt:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat&a=prompt&ajax=1",
				dataType:"json",
				success:function(res){
					that.tsList=res.data.list;
				}
			})
		},
		promptAsk:function(ask){
			this.clear();
			this.content=ask.description;
			this.showPrompt=false;
			this.send();
		},
		getLog:function(){
			var that=this;
			that.showLog=true;
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.logList=res.data.list;
				}
			})
		},
		goLog:function(item){
			this.id=item.id;
			this.getMsg();
			this.showLog=false;
		},
		blurContent:function(){
			var that=this;
			setTimeout(function(){
				that.conHeight='40px'
			},300)
		},
		clear:function(){
			this.id=0;
			this.msgList=[];
			this.inpost=0;
			this.chatimg_first=true;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_bchat&ajax=1",
				dataType:"json",
				success:function(res){
					 
					 
				}
			})
		},
		getMsg:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=getmsg&ajax=1",
				dataType:"json",
				data:{
					id:this.id
				}, 
				success:function(res){
					if(res.error){
						return false;
					}
					that.msgList2=JSON.parse(JSON.stringify(res.data.msgList)) 
					 
					
					that.msgList=JSON.parse(JSON.stringify(res.data.msgList)) 
					for(var i in that.msgList){
						 
						var ans=marked.parse(that.msgList[i].content)
						that.msgList[i].content=ans
					}
					if(res.data.stream_status==1){
						clearInterval(that.timer);
						that.inpost=false;
					}
					 
					clearInterval(that.inpost_timer);
					that.$nextTick(function(){
						hljs.highlightAll();
						let scrollEl = that.$refs.msglist;
						scrollEl.scrollTo({ top: scrollEl.scrollHeight+150, behavior: 'smooth' });
					})
					 
				}
			})
		},
		send:function(){
			 
			var that=this;
			if(this.content==''){
				return false;
			}
			that.inpost=true;
			var img;
			if(this.chatimg_first){
				img=this.true_oimg;
			}else{
				img="";
			}
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=send&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					prompt:this.content,
					img:img,
					id:this.id
				},
				success:function(res){
					that.chatimg_first=false;
					 if(res.error){
						 skyToast(res.message);
						 return false;
					 }
					var ans=marked.parse(that.content)
					that.msgList.push({
						role:"ask",
						content:ans
					}) 
					that.msgList.push({
						role:"answer",
						createing:true,
						content:"正在努力创作中...."
					}) 	
					that.id=res.data.id;
					clearInterval(that.timer);
					that.timer=setInterval(function(){
						that.getMsg();
					},2000)
					that.inpost_timer=setInterval(function(){
						that.inpost_time--;
						if(that.inpost_time<0){
							that.inpost_time=30;
						}
					},1000)
					that.content="";
					that.$nextTick(function(){
						hljs.highlightAll();
						let scrollEl = that.$refs.msglist;
						 
						scrollEl.scrollTo({ top: scrollEl.scrollHeight+150, behavior: 'smooth' });
					})
				}
			})
		},
		tts:function(item){
			var that=this;
			var audio=new Audio();
			audio.src="/module.php?m=tts&a=api&word="+encodeURI(item.content)
			audio.oncanplay=function(){
				audio.play();
			}
		},
		reask:function(index){
			this.content=this.msgList2[index-1].content;
			 
			this.send();
		},
		copy:function(index){
			this.content=this.msgList2[index].content;
		 
		},
		delItem:function(index,item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=delitem&ajax=1",
				dataType:"json",
				data:{
					id:this.id,
					index:index
				},
				success:function(res){
					that.getMsg()
				}
			})
		},
		upClick:function(){
			$("#upimg").click();
		},
		uploadImg:function(e){
			var that=this;
			that.clear();
			
			var src, url = window.URL || window.webkitURL || window.mozURL;
			var	file = e.target.files[0];
			var vFD = new FormData();
			vFD.append('upimg', file);
			// create XMLHttpRequest object, adding few event listeners, and POSTing our data
			var oXHR = new XMLHttpRequest();        
			oXHR.addEventListener('load', function(e){
				var res=eval("("+e.target.responseText+")");
				if(res.error){
					return false;
				}
				that.true_oimg=res.data.trueimgurl;
				that.oimg=res.data.imgurl;
				that.content="详细描述一下这张图片：![]("+res.data.trueimgurl+".small.jpg)"
				that.send();
			}, false);
			oXHR.addEventListener('error', function(e){
				
			}, false);
			 
			oXHR.open('POST',"/index.php?m=upload&a=img");
			oXHR.send(vFD);
		},
	}
})