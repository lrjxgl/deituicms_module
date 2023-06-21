Vue.component('page-guest',{
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			type:"all"
		}
	},
	created:function(){
	 
		this.getPage();
	},
	methods:{
		goDetail:function(item){
			window.location="/module.php?m=youyao_guest&a=user&shopid="+item.shopid
		},
		getPage:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=youyao_guest&ajax=1",
				success:function(res){
					that.isFirst=false;
					that.pageLoad=true;
					that.list=res.data.list;
					that.per_page=res.data.per_page;					 
				}
			})
		},
		 
		getList:function(){
			var that=this;
			if(!that.isFirst && that.per_page==0) return false;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=youyao_guest&ajax=1",
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
	},
	template:`
		<div>
			<div v-if="list.length==0" class="emptyData">暂无消息</div>
			<div @click="goDetail(item)" v-for="(item,index) in list" :key="index"  class="flexlist-item pointer">
				<img class="flexlist-img" :src="item.shop_imgurl+'.100x100.jpg'" />
				<div class="flex-1">
					<div class="flex mgb-5">
						<div class="flex-1 f16 cl2">{{item.shopname}}</div>
						<div class="cl3 f12">{{item.timeago}}</div>
					</div>
					<div class="flexlist-desc">{{item.content}}</div>
				</div>
			</div>
			<div class="loadMore" @click="getList" v-if="per_page>0">加载更多</div>
		</div>
		
	`
})
