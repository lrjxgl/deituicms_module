Vue.component("forum-item",{
	props:{
		dataList:Array
	},
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		 
		this.list=this.dataList;
	},
	watch:{
		dataList:function(n,o){
			this.list=n;
			 
		}
	},
	methods:{
		goUserHome:function(userid){
			window.location="/module.php?m=forum_home&userid="+userid
		},
		goDetail:function(id){
			window.location="/module.php?m=forum&a=show&id="+id
		},
		goGroup:function(gid){
			window.location="/module.php?m=forum&a=list&gid="+gid
		},
	},
	template:`
	<div>
		<div v-for="(item,index) in list" :key="index"  class="sglist-item">
			 
			<div class="pointer" @click="goDetail(item.id)">
				
				<div v-if="!item.imgslist || item.imgslist.length==0" class="sglist-title mgb-10">{{item.title}}</div>
				<template v-else> 
					<div v-if="item.imgslist.length==1">
						<div class="flex">
							<div class="sglist-title flex-1 mgb-10">{{item.title}}</div>
							<img v-for="(cc,ii) in item.imgslist" :key="ii" :src="cc+'.100x100.jpg'" class="w150 mgl-10" />
						</div>
					</div>
					
					<div v-else-if="item.imgslist.length<=3" >
						<div class="sglist-title mgb-10">{{item.title}}</div>
						<div class="sglist-imglist">
						<img v-for="(cc,ii) in item.imgslist" :key="ii" :src="cc+'.100x100.jpg'" :class="'sglist-imglist-img'+item.imgslist.length" />
						</div> 
					</div>
					<div v-else>
						<div class="sglist-title mgb-10">{{item.title}}</div>
						<div class="sglist-imglist">
						<img v-for="(cc,ii) in item.imgslist" :key="ii" :src="cc+'.100x100.jpg'" class="sglist-imglist-img" />
						</div>
					</div>
				</template>
				<div class="sglist-ft">
					<div class="sglist-ft-love">{{item.love_num}}</div>
					<div class="sglist-ft-cm">{{item.comment_num}}</div>
					<div class="sglist-ft-view">{{item.view_num}}</div>
				</div>
			</div> 
		</div>
	</div>	
	`
})