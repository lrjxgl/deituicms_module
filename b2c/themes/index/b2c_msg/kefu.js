Vue.component('page-kefu',{
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			type:"all",
			content:"",
		}
	},
	created:function(){
	 
		this.getPage();
		var that=this;
		var timer=sessionStorage.getItem("pageTimer");
		if(timer>0){
			clearInterval(timer)
		}
		timer=setInterval(function(){
			that.getPage();
		},10000)
		sessionStorage.setItem("pageTimer",timer)
	},
	 
	methods:{
		 
		getPage:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/index.php?m=kefu&a=data&ajax=1",
				success:function(res){
					that.isFirst=false;
					that.pageLoad=true;
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					 that.$nextTick(function(){
						window.scrollTo(0,10000);
					 })
				}
			})
		},
		 
		getList:function(){
			var that=this;
			if(!that.isFirst && that.per_page==0) return false;
			$.ajax({
				dataType:"json",
				url:"/index.php?m=kefu&a=data&ajax=1",
				data:{
					per_page:that.per_page,
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page=res.data.per_page;						
				}
			})
		},
		submit:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=kefu&a=save&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					content:this.content
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.content="";
					that.getPage();
				}
			})
		}
	},
	template:`
		<div>
			<div   class="list" >
				 
				<div v-for="(item,index) in  list" :key="index" class="pd-10">
					 
					<div  v-if="item.tablename=='user'">
						<div class="flex">
							<div class="flex-1"></div>
							<div class="cl2">我</div>
						</div>
						<div class="flex">
							<div class="flex-1"></div>
							<div class="kf-content mgr-20">{{item.content}}</div>
						</div>
					</div>
					<div  v-else>
						<div class="cl2 mgb-5">
							
							客服
						</div>
						<div class="kf-content mgl-20">{{item.content}}</div>
				
				 
					</div>
				</div>
				 
			</div>
			<div style="height:90px;"></div>
			<div style="position: fixed;bottom: 50px;left: 0;right: 0;">
				<div class="input-flex">
					<input type="text" v-model="content" class="input-flex-text" />
					<div class="input-flex-btn" @click="submit()">发送</div>
				</div>
			</div>
		</div>
		
	`
})
