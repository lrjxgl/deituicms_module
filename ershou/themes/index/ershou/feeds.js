Vue.component("page-feeds",{
	 
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_feeds&a=list&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=ershou_feeds&a=list&ajax=1",
				data:{

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
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		goItem:function(item){
			switch(item.tablename){
				case "mod_ershou_product":
					window.location="/module.php?m=ershou_product&a=show&productid="+item.productid
				break;
				case "mod_group_title":
					window.location="/module.php?m=group_title&a=show&id="+item.id
				break;
			}
		}
	},
	template:`
	<div>
		<div>
			<div @click="goItem(item)" v-for="(item,index) in list" :key="index" class="blogItem">
				<template v-if="item.tablename=='mod_ershou_product'">
				<img :src="item.user.user_head+'.100x100.jpg'" class="blogItem-head" />
				<div class="flex-1">
					<div class="flex mgb-5">
						<div class="blogItem-hnick">{{item.user.nickname}}</div>
						<div class="blogItem-htag">粉丝最爱</div>
					</div>
					<div class="cl3 mgb-5 f12">{{item.timeago}}给宝贝降了价</div>
					<div class="flex flex-ai-end mgb-5">
						<div class="blogItem-cl-money f14">降价至</div>
						<div class="blogItem-cl-money">￥</div>
						<div class="blogItem-cl-money f18">{{item.price}}</div>
						<div class="blogItem-market-pirce">￥{{item.market_price}}</div>
					</div>
					<div class="inlineblock mgb-5">
						<span class="blogItem-tag">降价</span>
						<span>{{item.description}}</span>
					</div>
					<div class="flex mgb-5">
						<img v-for="(cc,ccindex) in item.imgList" :key="ccindex" :src="cc+'.100x100.jpg'" class="blogItem-img" />
						 
					</div>
					<div>
						<div class="flex  mgb-5">
							<div class="flex mgr-10">
								<img v-for="(cm,cmindex) in item.cmList" :key="cmindex"  :src="cm.user_head+'.100x100.jpg'" class="blogItem-cm-head" />
							</div>
							<div class="cl3 f12">{{item.comment_num}}条留言 · {{item.love_num}}人想要 · {{item.view_num}}人浏览</div>
						</div>
						<div class="blogItem-cmbox">
							<div v-for="(cm,cmindex) in item.cmList" :key="cmindex" class="flex flex-ai-center">
								<div>{{cm.nickname}}：</div>
								<div class="cl3 f12">{{cm.content}}</div>
							</div>
							 
						</div>
					</div>
				</div>
				</template>
				
				<template v-else>
					<img :src="item.user_head+'.100x100.jpg'" class="blogItem-head" />
					<div class="flex-1">
						<div class="flex mgb-5">
							<div class="blogItem-hnick">{{item.nickname}}</div>
							 
						</div>
						<div class="d-content mgb-5" v-html="item.content"></div> 
						<div class="flex mgb-5">
							<img v-for="(cc,ccindex) in item.imgList" :key="ccindex" :src="cc+'.100x100.jpg'" class="blogItem-img" />
							 
						</div>
						<div>
							<div class="flex  mgb-5">
								<div class="flex mgr-10">
									<img v-for="(cm,cmindex) in item.cmList" :key="cmindex"  :src="cm.user_head+'.100x100.jpg'" class="blogItem-cm-head" />
								</div>
								<div class="cl3 f12">{{item.comment_num}}条留言 · {{item.love_num}}人想要 · {{item.click_num}}人浏览</div>
							</div>
							<div class="blogItem-cmbox">
								<div v-for="(cm,cmindex) in item.cmList" :key="cmindex" class="flex flex-ai-center">
									<div>{{cm.nickname}}：</div>
									<div class="cl3 f12">{{cm.content}}</div>
								</div>
								 
							</div>
						</div>
					</div>
				</template>
			</div>
	</div>
	`
})