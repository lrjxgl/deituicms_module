Vue.component("page-index",{
	data:function(){
		return {
			groupList:[],
			id:0,
			list:[],
			tagList:[],
			tagid:0,
			per_page:0,
			isFirst:true
		}
	},
	created:function(){
		var that=this;
		if(!pageCache.getCache(this,'ershou_wan_page_index')){
			this.getPage();
			this.getTagList();
		}
		
	},
	 
	methods:{
		 
		setTag:function(e){
			this.tagid=e;
			if(e==0){
				this.getPage();
			}else{
				this.isFirst=true;
				this.per_page=0;
				this.getList();
			}
			pageCache.setCache(this,'ershou_wan_page_index');
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group&ajax=1",
				dataType:"json",
				success:function(res){
					that.groupList=res.data.data;
					that.list=res.data.topiclist; 
					pageCache.setCache(that,'ershou_wan_page_index');
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=group_utag&a=show&ajax=1",
				data:{
					tagid:this.tagid,
					per_page:that.per_page,
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
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
					pageCache.setCache(that,'ershou_wan_page_index');
				}
			})
		},
		getTagList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_utag&ajax=1",
				dataType:"json",
				success:function(res){
					that.tagList=res.data.list;
				}
			})
		},
		goGroup:function(gid){
			window.location="/module.php?m=group&a=show&gid="+gid
		},
		goBlog:function(item){
			window.location="/module.php?m=group_title&a=show&gid="+item.gid+"&id="+item.id
		}
	},
	template:`
	<div>
		 
		
		<div class="row-box mgb-10">
			<div class="flex flex-ai-center mgb-10">
				<div class="cl2">我常逛的圈子</div>
				<div class="flex-1"></div>
				<div gourl="/module.php?m=ershou_group" class="cl2">全部</div>
				<div class="iconfont icon-right cl2"></div>
			</div>
			
			<div class="flex">
				<template  v-for="(item,index) in groupList" :key="index">
					<div @click="goGroup(item.gid)" class="flex flex-ai-center mgr-10" v-if="index<2" >
						<img :src="item.glogo+'.100x100.jpg'" class="w50 bd-radius-10 mgr-5" />
						<div class="flex-1">
							<div class="mgb-5 fw-600">{{item.gname.substr(0,12)}}</div>
							<div class="cl2 f12">10更新</div>
						</div>
					</div>
				</template>
				
			</div>
		</div>
		
		<div class="row-box">
			<div style="overflow:auto;" class="tabs-border mgb-10">
				<div :class="tagid==0?'tabs-border-active':''" @click="setTag(0)" class="mgr-20">猜你喜欢</div>
				<div :class="tagid==item.tagid?'tabs-border-active':''" @click="setTag(item.tagid)"  v-for="(item,index) in tagList" :key="index" class="mgr-20">{{item.title}}</div>
				 
			</div>
			<div class="emptyData" v-if="list.length==0">暂无数据</div>
			<div v-else>
				<div  class="flex">
					<div class="flex-1 mgr-10">
						<template  v-for="(item,index) in list" :key="index">
							<div @click="goBlog(item)" class="mgb-20 pointer" v-if="index%2==0">
								<img v-if="item.imgurl!=''" :src="item.imgurl+'.middle.jpg'" class="wmax mgb-5 bd-radius-20" />
								<div class=" mgb-5 d-content"  v-if="item.description!=''">{{item.description}}</div>
								<div v-else v-html="item.content"></div>
								<div class="flex flex-ai-center">
									<img :src="item.user_head+'.100x100.jpg'" class="w30 bd-radius-50 mgr-5" />
									<div class="cl3">{{item.nickname}}</div>
									<div class="flex-1"></div>
									<div class="iconfont icon-like cl3 mgr-5"></div>
									<div class="cl3">{{item.love_num}}</div>
								</div>
							</div>
						</template> 
					</div>
					<div class="flex-1">
						<template  v-for="(item,index) in list" :key="index">
							<div @click="goBlog(item)" class="mgb-20 pointer" v-if="index%2==1">
								<img v-if="item.imgurl!=''" :src="item.imgurl+'.middle.jpg'" class="wmax mgb-5  bd-radius-20" />
								<div class=" mgb-5 d-content"  v-if="item.description!=''">{{item.description}}</div>
								<div v-else v-html="item.content"></div>
								<div class="flex flex-ai-center">
									<img :src="item.user_head+'.100x100.jpg'" class="w30 bd-radius-50  mgr-5" />
									<div class="cl3">{{item.nickname}}</div>
									<div class="flex-1"></div>
									<div class="iconfont icon-like cl3 mgr-5"></div>
									<div class="cl3">{{item.love_num}}</div>
								</div>
							</div>
						</template> 
					</div>
				</div>
				<div class="loadMore" v-if="per_page>0" @click="getList()"></div>
			</div>
		</div>
	</div>
	`
})