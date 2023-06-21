wsApp.getWsKey();
var wsclient_to="kefuID"+kfid;
wsApp.wsInit();
var app = new Vue({
	el: "#app",
	data: function() {
		return {
			pageLoad: false,
			user:{},
			kefu:{},
			list:[],
			olist:[],
			listMaxId:30,
			listArrayIndex:30,
			listArray:[],
			isFirst: true,
			per_page: 0,
			hasNew: false,
			content:"",
			showGift: false,
			emoClass: "none",
			emoList:[],
			bigModal:false,
			bigImg:"",
			fileurl:"",
			filetype:"",
			videoModal:false,
			videourl:""
		}
	},
	created: function() {
		var that=this;
		this.getPage();
		this.getNew();
		wsApp.wsMessage=function(e){
			if(e.page!=undefined && e.page=="kefu/kefu_msg"){
				if(e.wsclient_to!=wsApp.wsKey){
					return false;
				}
				if(e.type=="say"){
					that.list.push(e);
					//that.getNew();
				}
			}
		}
		this.emoList=EMO.emoList();
		console.log(this.emoList)
	},
	watch:{
		list:function(n,o){
			this.$nextTick(function(){
			 
				setTimeout(function(){
					window.scrollTo(0,10000);
				},100)
				 
			})
		},
		listArray:function(n,o){
			this.$nextTick(function(){
				var i=this.listArrayIndex+1;
				if(i==30){
					var top=$(".new").offset().top;
				}else{
					var top=$("#oitem"+i).offset().top;
				}
				
				window.scrollTo(0,top+50);
				 
			})
		}
	},
	methods: {
		showBig:function(imgurl){
			this.bigModal=true;
			this.bigImg=imgurl
		},
		showVideo:function(url){
			this.videoModal=true;
			this.videourl=url
		},  
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=kefu_msg&ajax=1",
				data: {
					kfid: kfid,
					userid: userid
				},
				dataType: "json",
				success: function(res) {
					that.user=res.data.user;
					that.kefu=res.data.kefu;				
					that.pageLoad = true;
				}
			})
		},
		getList: function() {
			var that = this;
			if (!that.isFirst && that.per_page == 0) return false;
			$.ajax({
				dataType: "json",
				url: "/module.php?m=kefu_msg&a=list&ajax=1",
				data: {
					per_page: that.per_page,
					kfid: kfid,
					userid: userid
				},
				success: function(res) {
					if (that.isFirst) {
						var list=[];
						for(var i in res.data.list){
							var item=res.data.list[i];
							item.content=EMO.decodeEmo(item.content);
							list.push(item);
						}
						that.list = res.data.list;
						
						that.isFirst = false;
					} else {
						
						for (var i in res.data.list) {
							var item=res.data.list[i];
							item.content=EMO.decodeEmo(item.content);
							that.olist.push(item);
						}
					}
					that.per_page = res.data.per_page;
					
				}
			})
		},
		getOldList: function() {
			var that = this;
			if (this.per_page == 0) return false;
			that.loadIng = true;
			$.ajax({
				dataType: "json",
				url:"/module.php?m=kefu_msg&a=list&ajax=1",
				data: {
					
					per_page: that.per_page,
					kfid: kfid,
					userid: userid
				},
				success: function(res) {
					that.per_page = res.data.per_page;
					var list=[];
					
					for(var i in that.listArray){
						list.push(that.listArray[i]);
					}
					list[that.listArrayIndex] = {list: res.data.list};
					that.listArray=list;
					that.listArrayIndex--;
				}
			})
		},
		getNew:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		submit: function() {
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=kefu_msg&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content: this.content,
					fileurl:this.fileurl,
					filetype:this.filetype,
					kfid: kfid,
					userid: userid
				},
				success:function(res){
					var msg=JSON.stringify({
						wsclient_to: wsclient_to,
						type: "say",
						page:"kefu/kefu_msg",
						content: that.content,
						fileurl:that.fileurl,
						filetype:that.filetype,
						kfid:kfid,
						userid:userid,
						ukey:"user"
					})
					var msgItem={
						content:that.content,
						fileurl:that.fileurl,
						filetype:that.filetype,
						kfid:kfid,
						userid:userid,
						ukey:"user"
					}
					console.log(msgItem);
					that.list.push(msgItem);
					wsApp.ws.send(msg);
					that.content="";
					that.fileurl="";
					that.filetype="";
					//that.getNew();
				}
			}) 
			
		},
		addEmo: function(s) {
			s = "\\" + s + " ";
			this.content += s;
		},
		downFile:function(url){
			var a=document.createElement("a");
			a.setAttribute("target","_blank");
			a.setAttribute('download', '');// download属性
			a.setAttribute('href', url);
			a.click();
		},
		choiceImg: function() {
			var that = this;
			$("#upimg").click();
		},
		uploadImg:function(){
			var that=this;
			this.skyUpload("upimg","/index.php?m=upload&a=img",function(e){
				var res=JSON.parse(e.target.responseText);
				if(!res.error){					
					that.content='';
					that.fileurl=res.data.imgurl;
					that.filetype="img";
					that.submit()
				}
				
			},function(e){
				console.log(e)
			},function(e){
				console.log(e)
			})
		},
		choiceVideo:function(){
			$("#upVideo").click();
		},
		uploadVideo:function(){
			var that=this;
			var fdata = new FormData();
			
			$.get("/index.php?m=ossupload&a=auth&ajax=1", function(data) {
				var file = document.querySelector("#upVideo").files[0];
				console.log(file)
				if (file == undefined) {
					console.log("Empty");
					return false;
				}
			
				fdata.append("OSSAccessKeyId", data.accessid);
				fdata.append("policy", data.policy);
				fdata.append("Signature", data.sign);
				fdata.append("key", data.key + file.name);
				fdata.append("callback", data.callback);
			
				fdata.append("file", file);
				console.log(data.url)
				$.ajax({
					url: data.url,
					type: 'POST',
					data: fdata,
					contentType: false,
					processData: false,
					dataType: "json",
					 
					success: function(res) {
						that.content='';
						that.fileurl=res.filename;
						that.filetype="video";
						that.submit()
						 
			
					},
					error: function(returndata) {
						console.log(returndata);
			
					}
				});
			}, "json")
		},
		choiceFile: function() {
			$("#upFile").click();
		},
		
		uploadFile:function(){
			var that=this;
			this.skyUpload("upFile","/index.php?m=upload&a=uploadfile",function(e){
				var res=JSON.parse(e.target.responseText);
				if(!res.error){					
					that.content='';
					that.fileurl=res.imgurl;
					that.filetype="file";			
					that.submit()
				}
			},function(e){
				console.log(e)
			},function(e){
				console.log(e)
			})
		},
		error:function(e){},
		uploadProgress:function(e){},
		skyUpload: function(upid, url, success, error, uploadProgress) {
			var vFD = new FormData();
			var f = document.getElementById(upid).files;
			$("#" + upid + "loading").show();
			for (var i = 0; i < f.length; i++) {
				vFD.append('upimg', document.getElementById(upid).files[i]);
				// create XMLHttpRequest object, adding few event listeners, and POSTing our data
				var oXHR = new XMLHttpRequest();
				oXHR.addEventListener('load', success, false);
				oXHR.addEventListener('error', error, false);
				if (uploadProgress != undefined) {
					oXHR.upload.addEventListener("progress", uploadProgress, false);
				}
				oXHR.open('POST', url);
				oXHR.send(vFD);
		
			}
		}
	}
})
var inOld=false;
$(window).on("scroll",function(e){
	
	if(window.scrollY<=60){
		if(!inOld){
			inOld=true;
			app.getOldList();
			
			setTimeout(function(){
				inOld=false;
			},2000);
		}
		
	}
}) 