var app=new Vue({
	el:"#App",
	data:function (){
		return {
			content:"",
			conHeight:'36px',
			tabList:[{
				title:"默认",
				inpost:false,
				postTimer:60,
				timer:0,
				msgList:[]
			}],
			orgMsgList:[{
				title:"默认",
				
				msgList:[]
			}],
			tabIndex:0,
			 
			inpost:false,
			postTimer:30,
			timer:0,
			showPrompt:false,
			tsList:[],
			tab_name:"",
			showTagAdd:false,
			tagid:0
		}
	},
	created:function(){
		 this.getPrompt();
		 this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_chat_tag&a=mytab&ajax=1",
				dataType:"json",
				success:function(res){
					
					that.tagid= res.data.list[0].tagid;
					var list=JSON.parse(JSON.stringify(res.data.list));
					//处理过的数据
					for(var i in list){
						list[i].inpost=false;
						list[i].postTimer=60;
						list[i].timer=0;
						 
					}				
					that.tabList=list;
					//原始数据
					var olist=JSON.parse(JSON.stringify(res.data.list));
				 
					that.orgMsgList=olist;
					console.log(that.tabList,that.orgMsgList); 
					 
				}
			})
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
			this.ask();
		},
		saveTab:function(){
			if(this.tab_name==''){
				return false;
			}
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_chat_tag&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					title:this.tab_name
				},
				success:function(res){
					that.tab_name="";
					that.showTagAdd=false;
					that.getPage()
				}
			})
			 
		},
		setTab:function(index,tagid){
			this.tabIndex=index;
			this.tagid=tagid 
			this.inpost=false;
			this.postTimer=60;
			clearInterval(this.timer)
		},
		clear:function(){
			var that=this;
			if(confirm("确认清除记录吗?")){
				that.conHeight='36px'
				that.tabList[that.tabIndex].msgList=[];
				that.orgMsgList[that.tabIndex].msgList=[];
			}
			
		}, 
		blurContent:function(){
			var that=this;
			setTimeout(function(){
				that.conHeight='36px'
			},300)
		},
		ask:function(){
			var that=this;
			this.conHeight='36px'
			if(this.content==''){
				return false;
			}
			var tb=this.tabList[this.tabIndex];
			if(tb.inpost){
				return false;
			}
			tb.inpost=true;
			tb.postTimer=60;
			if(tb.timer>0){
				clearInterval(tb.timer)
				tb.timer=0;
				
			}
			this.tabList[this.tabIndex]=tb;
			tb.timer=setInterval(function(){
				 tb.postTimer--;
				 if(tb.postTimer<0){
					 tb.postTimer=60;
					 tb.inpost=false;
				 }
				
			 },1000)
			 
			
			that.tabList[that.tabIndex].msgList.push({
					role:"ask",
					content:this.content
			});
			 
			that.orgMsgList[that.tabIndex].msgList.push({
					role:"ask",
					content:this.content
			});
			var content=that.content;
			that.content='';
			var oldMsg=[];
			var msgList=that.orgMsgList[that.tabIndex].msgList;
			console.log(msgList);
			for(var i in msgList){
				oldMsg.push({"role":msgList[i].role=='ask'?'ask':'answer',"content":msgList[i].content});
			}
			
			$.ajax({
				url:"/module.php?m=aichat_chat&a=api&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:content,
					oldMsg:oldMsg,
					tabIndex:this.tabIndex,
					tagid:this.tagid
				},
				error:function(e){
					skyToast("发生错误，请重新提交")
				},
				success:function(res){
					
					 
					if(res.error){
						return false;
					}
					var rtb=that.tabList[res.data.tabIndex];
					rtb.inpost=false;
					var ans=Help.htmlspecialchars(res.data.answer);
					ans=ans.replace(/```(\w+)/g,"<pre class=\"preblock\">")
					ans=ans.replace(/```(\W)/g,"</pre>")
					//保留原始数据
					that.orgMsgList[res.data.tabIndex].msgList.push({
							role:"answer",
							content:res.data.answer
					});
					//处理过的数据
					that.tabList[res.data.tabIndex].msgList.push({
							role:"answer",
							content:ans
					});
					console.log(that.tabList[res.data.tabIndex].msgList)
					that.$nextTick(() => {
					     let scrollEl = that.$refs.msglist;
					     scrollEl.scrollTo({ bottom: scrollEl.scrollHeight, behavior: 'smooth' });
					   });
				}
			})
		},
		del:function(index){
			console.log(index)
			var list=[];
			for(var i in this.tabList[this.tabIndex].msgList){
				if(i!=index){
					list.push(this.tabList[this.tabIndex].msgList[i])
				}
			}
			this.tabList[this.tabIndex].msgList=list;
			//处理原始数据
			var olist=[];
			for(var j in this.orgMsgList[this.tabIndex].msgList){
				if(j!=index){
					olist.push(this.orgMsgList[this.tabIndex].msgList[j])
				}
			}
			this.orgMsgList[this.tabIndex].msgList=olist;
		 
		}
	}
})