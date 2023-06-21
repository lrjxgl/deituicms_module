var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			data:{},
			id:0,
			gid:0,
			cmList:[],
			ssuser:{},
			group:{},
			per_page:0,
			isFirst:true,
			cm_content:"",
			cm_pid:0,
			cm_imgurl:"",
			cmForm:false,
			emoList:["&#128512;","&#128514;","&#128517;","&#128522;","&#128525;","&#128526;","&#128534;"],
			cm_pics:[],
			cm_imgsdata:"",
			isFav:0,
			isLove:0,
			isFollow:0,
			openData:{}
		}
	},
	created:function(){
		this.id=id;
		this.getPage();
		this.getList();
		this.getFav();
		this.getLove();
		
	},
	watch:{
		cm_pics:function(n,o){
			this.cm_imgsdata="";
			for(var i in n){
				if(parseInt(i)>0){
					this.cm_imgsdata+=","
				}
				this.cm_imgsdata +=n[i].imgurl;
			}
		}
	},
	methods:{
		getOpenData:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_open_data&a=show&ajax=1",
				dataType:"json",
				data:{
					open_data:this.data.open_data
				},
				success:function(res){
					that.openData=res.data.data;
					 
				}
			})
		},
		goGroup:function(gid){
			window.location="/module.php?m=group&a=show&gid="+gid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_title&a=show&ajax=1",
				dataType:"json",
				data:{
					id:this.id
				},
				success:function(res){
					that.data=res.data.data;
					that.gid=res.data.data.gid;
					that.pageLoad=true;
					that.group=res.data.group;
					if(that.data.open_data!=''){
						that.getOpenData()
					}
					that.getFollow();
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_comment&ajax=1",
				dataType:"json",
				data:{
					id:this.id
				},
				success:function(res){
					 
					that.cmList=res.data.list;
				}
			})
		},
		getFollow:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=isFollow&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.data.userid
				},
				success:function(res){
					that.isFollow=res.data;
					 
				}
			})
		},
		followToggle:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=toggle&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.data.userid
				},
				success:function(res){
					if(!res.error){
						that.isFollow=res.follow; 
					}
					
					 
				}
			})
		},
		getFav:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=fav&a=isfav&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_group_title",
					objectid:this.id
				},
				success:function(res){
					that.isFav=res.data;
					 
				}
			})
		},
		favToggle:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=fav&a=toggle&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_group_title",
					objectid:this.id
				},
				success:function(res){
					 
					if(res.data=='delete'){
						that.isFav=0;
						that.data.fav_num-=1;
					} else{
						that.isFav=1;
						that.data.fav_num+=1;
					}
				}
			})
		},
		
		getLove:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=love&a=islove&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_group_title",
					objectid:this.id
				},
				success:function(res){
					that.isLove=res.data;
					 
				}
			})
		},
		loveToggle:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=love&a=toggle&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_group_title",
					objectid:this.id
				},
				success:function(res){
					 
					if(res.data=='delete'){
						that.isLove=0;
						that.data.love_num-=1;
					} else{
						that.isLove=1;
						that.data.love_num+=1;
					}
				}
			})
		},
		cmLoveToggle:function(item){
			var that=this;
			$.ajax({
				url:"/index.php?m=love&a=toggle&ajax=1",
				dataType:"json",
				data:{
					objectid:item.id,
					tablename:"mod_group_comment"
				},
				success:function(res){
					if(res.data=='add'){
						item.love_num+=1;
					}else{
						item.love_num-=1;
					}
				}
			})
		},
		showForm:function(pid){
			this.cmForm=true;
			if(pid!=undefined){
				this.cm_pid=pid;
			}else{
				this.cm_pid=0;
			}
			
		},
		cmEmo:function(e){
			var n=e.substr(2,e.length-3);
			this.cm_content =  this.cm_content  +" " +String.fromCodePoint(n)+" ";
		},
		clickFile:function(upname){
			$("#"+upname).click();
		},
		upFile:function(e){
			var src, url = window.URL || window.webkitURL || window.mozURL,
				files = e.target.files;
				var that=this;
			for (var i = 0, len = files.length; i < len; ++i) {
				var file = files[i];
			
				if (url) {
					src = url.createObjectURL(file);
				} else {
					src = e.target.result;
				}
				lrz(file, {
						width: 1024
					}).then(function(rst) {
			
						$.post("/index.php?m=upload&a=base64", {
								content: rst.base64,
								tablename: "mod_shopmap",
								object_id: 0,
								inimgs: 0,
							},
							function(data) {
								that.cm_pics.push({
									imgurl:data.imgurl,
									trueimgurl:data.trueimgurl
								});
						 
							}, "json")
					})
					.catch(function(err) {
						console.log(err)
					})
			
			}
		},
		
		cmSubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_comment&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:this.cm_content,
					newsid:this.id,
					gid:this.gid,
					imgurl:this.cm_imgurl,
					pid:this.cm_pid,
					imgsdata:this.cm_imgsdata
				},
				success:function(res){
					
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.cm_content="";
					that.cm_imgsdata="";
					that.cm_pics=[];
					that.cm_imgurl="";
					that.cmForm=false;
					that.getList();
				}
			})
		}
	}
}) 