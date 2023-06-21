Vue.component("upload-data",{
	props:{
		upname:"",
		dList:[]
	},
	data:function(){
		return {
			imgList:[],
			 
		}
	},
	created:function(){
		this.imgList=this.dList
	},
	watch:{
		dList:function(n,o){
			this.imgList=n;
		},
		imgList:function(n,o){
			var imgdata="";
			for(var i in n){
				if(i>0){
					imgdata+=","
				}
				imgdata+=n[i].imgurl
			}
			 
			this.$emit("set-data",imgdata)
		}
	},
	methods:{
		del:function(item){
			var list=[];
			for(var  i in this.imgList){
				if(this.imgList[i]!=item){
					list.push(this.imgList[i])
				}
			}
			this.imgList=list;
		},
		left:function(item){
			var list=[];
			var len=this.imgList.length;
			for(var i in this.imgList){
				list.push(this.imgList[i])
			}
			for(i=0;i<len;i++){
				if(list[i]==item){				
					if(i>0){					 
						var temp=list[i-1];
						list[i-1]=item;
						list[i]=temp;
					}
				}
			}		 
			this.imgList=list;
			 
		},
		right:function(item){
			var list=[];
			var len=this.imgList.length;
			for(var i in this.imgList){
				list.push(this.imgList[i])
			}
			for(i=0;i<len;i++){
				if(list[i]==item){				
					if(i<len-1){					 
						var temp=list[i+1];
						list[i+1]=item;
						list[i]=temp;
					}
				}
			}		 
			this.imgList=list;
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
								that.imgList.push({
									imgurl:data.imgurl,
									trueimgurl:data.trueimgurl
								})
								 
						 
							}, "json")
					})
					.catch(function(err) {
						console.log(err)
					})
			
			}
		}
	},
	template:`
	<div>
		<div class="upimg-box">
			 
			<input @change="upFile" style="display: none;" multiple type="file" name="upimg" :id="upname" />
			 
			 
			
			<div v-for="(item,index) in imgList" :key="index" class="upimg-item">
				<img class="upimg-img" :src="item.trueimgurl+'.100x100.jpg'"/>
				<i @click="del(item)" class="upimg-del"></i>
				<div class="flex flex-center">
					<div @click="left(item)" class="upimg-goleft">&lt;</div>
					 
					<div @click="right(item)" class="upimg-goright">&gt;</div>
				</div>
			</div>
			<div  @click="clickFile(upname)"
				style="width: 10rem;height: 10rem;border:1px solid #eee; margin-right: 5px;align-items: center;justify-content: center;font-size: 1rem;color:#aaa;background-color: #f7f7f7;">
				+添加优质<br />首图更吸引人</div> 
			 
		</div>
		 
	</div>	
	`,
	 
})