var winWidth;
var winHeight;
msApp=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			zblive:{},
			proList:[],
			msgList:[],
			cmContent:"",
			product:{},
			productShow:false,
			m3u8url:"",
			mp4url:"",
			flvurl:"",
			iswap:0,
			videoWidth:375,
			videoHeight:200,
			ftBoxHeight:300,
			videoMaskTop:100,
			vdsize:1,
			newGoodOn:false,
			newGood:{},
			tab:"comment"
		}
	},
	created:function(){
		this.iswap=iswap;
		this.getPage();
		this.getMsgList();
		winWidth=$(window).width();
		winHeight=$(window).height();
		this.videoWidth=winWidth;
	 
		this.videoHeight=this.videoWidth*9/16;
		this.ftBoxHeight=Math.max(300,winHeight-this.videoHeight-30);
	},
	methods:{
		setTab:function(tab){
			this.tab=tab;
			
		},
		setVideoHeight:function(){
			if(this.vdsize==1){
				this.videoHeight=windowHeight;
				this.ftBoxHeight=Math.max(300,winHeight-this.videoHeight-30);
			}else{
				this.videoHeight=this.videoWidth*this.vdsize;
				this.ftBoxHeight=Math.max(300,winHeight-this.videoHeight-30);
			}
			
		},
		 
		videoPlay:function(){
			$("#videoPlay").hide();
		},
		getPage:function(){

			var that=this;
			$.ajax({
				url:"/module.php?m=zblive&a=show&ajax=1&id="+room_id,
				dataType:"json",
				success:function(res){
					ssuser=res.data.ssuser;
					that.zblive=res.data.zblive;
					that.getProList();
					that.pageLoad=true;
					that.m3u8url=res.data.m3u8url;
					that.mp4url=res.data.mp4url;
					if(that.zblive.zbstatus==1){
						var videoUrl=res.data.m3u8url;
					}else{
						var videoUrl=res.data.mp4url;
					} 
					that.vdsize=res.data.vdsize;
					that.setVideoHeight();
					that.$nextTick(function(){
						 player = videojs('video');
					})
					
				}
			})
		},
		getMsgList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zblive_msg&a=list&ajax=1&room_id="+room_id,
				dataType:"json",
				success:function(res){
					that.msgList=res.data.list;
					that.$nextTick(() => {
						$(".msgList").scrollTop(10000);
					})
				}
			})
		},
		sendMsg:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zblive_msg&a=save&ajax=1",
				data:{
					room_id:room_id,
					content:this.cmContent
				},
				type:"POST",
				dataType:"json",
				success:function(res){
					ws_send(that.cmContent);
					that.cmContent="";
					that.getMsgList(); 
				}
			})
		},
		getProList:function(){
			var that=this;
			if(that.zblive.tablename=='' || that.zblive.proids==''){
				return false;
			}
			$.ajax({
				url:"/module.php?m="+that.zblive.tablename+"&a=list&ajax=1&ids="+that.zblive.proids,
				dataType:"json",
				success:function(res){
					
					that.proList=res.data.list;
				}
			})
		},
		goProduct:function(id){
			window.location="/module.php?m="+this.zblive.tablename+"&a=show&id="+id;
		},	
		showQuan:function(item){
			this.productShow=true;
			this.product=item;
		}
	}
})