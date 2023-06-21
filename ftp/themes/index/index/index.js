var App=new Vue({
	el:"#App",
	data:function(){
		return {
			ftpid:0,
			list:[],
			file:"",
			dir:"",
			fileContent:"",
			parentDir:"",
			page:'list',
			add:{
				title:"",
				content:""
			},
			newDir:{
				title:""
			},
			rename_title:"",
			allowFile:"txt,html,js,css,php",
		}
	},
	created:function(){
		if(localStorage.getItem("ftpid")){
			this.ftpid=localStorage.getItem("ftpid");
			$(".header-title").html(localStorage.getItem("ftp_title"))
			console.log(this.ftpid)
			this.getList();
		}else{
			this.page="host";
		}
		
		 
	},
	methods:{
		setHost:function(e){
			this.ftpid=localStorage.getItem("ftpid");
			this.page="list";
			this.dir="";
			this.getList();
			localStorage.setItem("ftp_title",e.title)
			$(".header-title").html(e.title)
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ftp&a=list&ajax=1",
				dataType:"json",
				data:{
					dir:this.dir,
					ftpid:this.ftpid
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goDir:function(item){
			this.dir+="/"+item.name;
			this.getList();
		},
		goLastDir:function(){
			var index=this.dir.lastIndexOf("/");
			this.dir=this.dir.substr(0,index);
			this.getList();
		},
		editFile:function(item){
			var index=item.name.lastIndexOf(".");
			var ftype=item.name.substr(index+1);
			if(this.allowFile.indexOf(ftype)<0){
				skyToast("不支持"+ftype+"格式编辑")
				return false;
			}
			
			this.file=this.dir+"/"+item.name;
			var that=this;
			$.ajax({
				url:"/module.php?m=ftp&a=readfile&ajax=1",
				dataType:"json",
				data:{
					file:this.file,
					ftpid:this.ftpid
				},
				success:function(res){
					that.page="edit"
					that.fileContent=res.data.fileContent;
					that.$nextTick(function(){
						tabIndent.renderAll();
					})
				}
			})
		},
		saveFile:function(){
			var that=this;
			
			$.ajax({
				url:"/module.php?m=ftp&a=savefile&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					file:this.file,
					fileContent:this.fileContent,
					ftpid:this.ftpid
				},
				success:function(res){
					that.page="list"
					skyToast(res.message);
				}
			})
		},
		createFile:function(){
			var that=this;
			var index=this.add.title.lastIndexOf(".");
			var ftype=this.add.title.substr(index+1);
			if(this.allowFile.indexOf(ftype)<0){
				skyToast("不支持"+ftype+"格式编辑")
				return false;
			}
			$.ajax({
				url:"/module.php?m=ftp&a=createfile&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					file:this.dir+"/"+this.add.title,
					fileContent:this.add.content,
					ftpid:this.ftpid
				},
				success:function(res){
					
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.add={}
					that.page="list";
					that.getList();
				}
			})
		},
		renameFile:function(item){
			this.file=this.dir+"/"+item.name;
			this.page="rename"
		},
		renameFileSave:function(){
			var that=this;
			
			$.ajax({
				url:"/module.php?m=ftp&a=renamefile&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					file:this.file,
					rename_title:this.rename_title,
					ftpid:this.ftpid
				},
				success:function(res){
					
					skyToast(res.message);
					if(res.error){
						return false;
					}
					
					that.rename_title="";
					that.page="list";
					that.getList()
				}
			})
		},
		delFile:function(item){
			var that=this;
			this.file=this.dir+"/"+item.name;
			skyJs.confirm({
				content:"确认删除文件吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=ftp&a=delfile&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							file:that.file,
							ftpid:that.ftpid
						},
						success:function(res){							
							skyToast(res.message);
							if(res.error){
								return false;
							}
							that.page="list";
							that.getList()
						}
					})
				}
			})
		},
		createDir:function(){
			var that=this;
			 
			$.ajax({
				url:"/module.php?m=ftp&a=createdir&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					dir:this.dir+"/"+this.newDir.title,
					 
					ftpid:this.ftpid
				},
				success:function(res){
					
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.newDir={}
					that.page="list";
					that.getList();
				}
			})
		},
		rmDir:function(item){
			var that=this;
			this.file=this.dir+"/"+item.name;
			skyJs.confirm({
				content:"确认删除目录吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=ftp&a=rmdir&ajax=1",
						dataType:"json",
						type:"POST",
						data:{
							file:that.file,
							ftpid:that.ftpid
						},
						success:function(res){							
							skyToast(res.message);
							if(res.error){
								return false;
							}
							that.page="list";
							that.getList()
						}
					})
				}
			})
		},
		choiceFile:function(){
			$("#uFile").click();
		},
		upFile:function(e){
			var that=this;
			var vFD = new FormData();
			var file= document.getElementById("uFile").files[0];
			vFD.append('upimg', file);
			vFD.append("ftpid",this.ftpid);
			vFD.append("dir",this.dir);
			var oXHR = new XMLHttpRequest();        
			oXHR.addEventListener('load', function(e){
				var res=eval("("+e.target.responseText+")");
				skyToast(res.message);
				if(!res.error){
					that.getList()
				}
			}, false);
			oXHR.addEventListener('error', function(e){
				
			}, false);
			oXHR.upload.addEventListener("progress", function(e){
				
			}, false);
			oXHR.open('POST',"/module.php?m=ftp&a=upfile&ajax=1");
			oXHR.send(vFD);
		}
	}
})