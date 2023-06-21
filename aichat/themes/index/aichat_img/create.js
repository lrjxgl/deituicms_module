var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			prompt:"",
			createLabel:"立马生成",
			createBgLabel:"后台生成",
			result:"",
			createTimer:0,
			inpost:false,
			article:{},
			showArticle:false,
			picModal:{},
			queue_num:0,
			my_queue_num:0,
			rscount:0,
			promptList:[],
			img_token:0
		}
	},
	created:function(){
		var that=this;
		if(window.innerWidth>500){
			this.picModal={width:'50rem',marginTop:'-30rem',left:'50%',marginLeft:'-25rem'}
		}
		this.getPage();
		this.getList();
		//this.getPrompt();
		that.getQueue();
		setInterval(function(){
			that.getQueue();
		},10000)
		
	},
	methods:{
		getPrompt:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_img_prompt&ajax=1&limit=6",
				
				dataType:"json",
				success:function(res){
					that.promptList=res.data.list;
				}
			})
		},
		goLast:function(){
			for(var i=0;i<this.list.length;i++){
				if(this.list[i].id==this.article.id){
					if(i!=0){
						this.article=this.list[i-1];
						break;
					}
				}
			}
		},
		goNext:function(){
			console.log("next",this.article,this.article.id)
			for(var i=0;i<this.list.length;i++){
				if(this.list[i].id== this.article.id){
					if(i<this.list.length-1){
						this.article=this.list[i+1];
						console.log(i+1)
						break;
					}
				}
			}
			console.log(this.article)
		},
		getQueue:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_img&a=queuenum&ajax=1",
				
				dataType:"json",
				success:function(res){
					that.queue_num=res.data.num;
					that.my_queue_num=res.data.mynum;
				}
			})
		},
		showItem:function(item){
			this.article=item;
			this.showArticle=true;
		},
		delItem:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_img&a=delete&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.showArticle=false;
					var list=[];
					for(var i in that.list){
						if(item.id!=that.list[i].id){
							list.push(that.list[i])
						}
					}
					that.list=list;
				}
			})
		},
		getImg:function(imgid){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_img&a=get&ajax=1",
				data:{
					id:imgid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(res.data.create_status==1){
						clearInterval(that.createTimer);
						that.inpost=false;
						that.createLabel="立马生成"
						that.result='<img class="w99" style="max-width:300px;" src="'+res.data.imgurl+'" />';
						that.getList()
					}
				}
			})
		},
		
		createImg:function(){
			var that=this;
			if(that.inpost){
				return false;
			}
			that.inpost=true;
			
			that.createLabel="正在马不停蹄的生图片...";
			that.result=""; 
			$.ajax({
				//url:"http://fd175.skymvc.com/chatgpt/dreamstudio.php",
				url:"/module.php?m=aichat_img&a=createsave&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					prompt:that.prompt
				},
				error:function(e){
					that.inpost=false;
					skyToast("发生错误")
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
					}else{
						//
						that.createTimer=setInterval(function(){
							that.getImg(res.data.id)
						},6000)
					}
					
					
				}
			})
		},
		copy:function(prompt){
			this.prompt=prompt;
			this.createImgBg()
		},
		createImgBg:function(){
			var that=this;
			if(that.inpost){
				return false;
			}
			that.inpost=true;
			that.createBgLabel="正在添加任务"
			
			$.ajax({
				url:"/module.php?m=aichat_img&a=createsave&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					prompt:that.prompt
				},
				error:function(e){
					that.inpost=false;
					skyToast("发生错误")
				},
				success:function(res){
					that.createBgLabel="后台生成";
					if(res.error){
						skyToast(res.message)
					}else{
						skyToast("任务添加成功，过段时间刷新");
					}
					that.getQueue();
					that.inpost=false;
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_img&a=create&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.img_token=res.data.img_token;
					
				}
			})
		},
		refresh:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=aichat_img&a=my&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					that.rscount=res.data.rscount;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		}
	}
})