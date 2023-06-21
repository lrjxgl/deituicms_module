Vue.component("page-comment",{
	data:function(){
		return {
			pageLoad:false,
			per_page:0,
			isFirst:true,
			productid:0,
			comment_num:0,
			cmList:[],
			cm_content:"",
			cm_pid:0,
			cm_imgurl:"",
			cmForm:false,
			emoList:["&#128512;","&#128514;","&#128517;","&#128522;","&#128525;","&#128526;","&#128534;"],
			cm_pics:[],
			cm_imgsdata:"",
		}
	},
	created:function(){
		this.productid=productid; 
		this.getList();
	 
		
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
		 
		 
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_product_comment&a=list&ajax=1",
				dataType:"json",
				data:{
					productid:this.productid
				},
				success:function(res){
					that.comment_num=res.data.rscount; 
					that.cmList=res.data.list;
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
				url:"/module.php?m=ershou_product_comment&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					content:this.cm_content,
					objectid:this.productid,
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
					that.cmForm=false;
					that.getList();
				}
			})
		},
		loveToggle:function(item){
			var that=this;
			$.ajax({
				url:"/index.php?m=love&a=toggle&ajax=1",
				dataType:"json",
				data:{
					objectid:item.id,
					tablename:"mod_ershou_product_comment"
				},
				success:function(res){
					if(res.data=='add'){
						item.love_num+=1;
					}else{
						item.love_num-=1;
					}
				}
			})
		}
	},
	template:`
	<div>
		<div class="mgb-20 fw-600">全部评论·{{comment_num}}</div>
		<div>
			<div v-for="(cm,index) in cmList" :key="index" class="flex flex-ai-start">
				<img :src="cm.user_head+'.100x100.jpg'" class="cmhead" />
				<div class="flex-1">
					<div class="flex mgb-5">
						<div class="mgr-10">{{cm.nickname}}</div>
						<div class="tag">作者</div>
					</div>
					<div @click="showForm(cm.id)" class="mgb-10" v-html="cm.content"></div>
					<div class="f12 mgb-10">{{cm.timeago}}</div>
					<!--回复列表-->
					<div v-if="cm.child && Object.keys(cm.child).length>0">
						<div v-for="(cc,ii) in cm.child" :key="ii" class="flex flex-ai-start">
							<img :src="cc.user_head+'.100x100.jpg'" class="cmhead" />
							<div class="flex-1">
								<div class="flex mgb-5">
									<div class="mgr-10">{{cc.nickname}}</div>
		
								</div>
								<div @click="showForm(cm.id)" v-html="cc.content" class="mgb-10"></div>
								<div class="f12 mgb-10">{{cc.timeago}}</div>
							</div>
							<div @click="loveToggle(cc)" class="mgl-10">
								<div class="iconfont icon-appreciate cl3"></div>
								<div class="cl3">{{cc.love_num}}</div>
							</div>
						</div>
		
					</div>
		
		
				</div>
				<div  @click="loveToggle(cm)" class="mgl-10">
					<div class="iconfont icon-appreciate cl3"></div>
					<div class="cl3">{{cm.love_num}}</div>
				</div>
			</div>
		</div>
		<!--评论框-->
		<div>
			<div class="flex">
				<input @click="showForm(0)" readonly placeholder="觉得好玩说两句" class="cmText" />
				<div class="flex" style="margin-left: -30px;">
					<div class="iconfont icon-appreciate"></div>
				</div>
			</div>
		</div>
		<div v-if="cmForm">
			<div @click="cmForm=false" class="modal-mask"></div>
			<div class="cmForm">
				<div class="flex pd-10 bg-white">
					<div @click="cmEmo(emo)" class="cmEmo" v-for="(emo,emoIndex) in emoList" v-html="emo">
					</div>
		
				</div>
				<div v-if="cm_pics.length>0" class="row-box">
					<div class=" flex">
						<div v-for="(img,index) in cm_pics" :key="index">
							<img :src="img.trueimgurl" />
						</div>
					</div>
				</div>
		
				<div style="padding: 10px; background-color: #fafafa;">
					<div class="flex flex-ai-center">
						<input v-model="cm_content"
							style="flex:1;border:0;outline:0; height: 30px; line-height: 30px; margin-right: 5px;"
							type="text" placeholder="聊两句呗" />
						<div @click="clickFile('news-comment-file')" class="iconfont icon-pic f22 mgr-10">
						</div>
						<input type="file" id="news-comment-file" name="upimg" class="none"
							@change="upFile" />
						<div @click="cmSubmit()" class="zbtn">发送</div>
					</div>
				</div>
		
			</div>
		</div>
	</div>
	`
}) 