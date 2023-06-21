Vue.component("page-blog",{
 
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			userid:0
		}
	},
	created:function(){
		this.userid=userid; 
		this.getPage();
	},
	methods:{
		goDetail:function(id){
			window.location="/module.php?m=fishing_blog&a=show&id="+id
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_blog&a=list&ajax=1",
				data:{
					userid:this.userid
				},
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
				url:"/module.php?m=fishing_blog&a=list&ajax=1",
				data:{
					userid:this.userid,
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
		}
	},
	template:`
		<div>
			<div class="emptyData" v-if="list.length==0">暂无帖子</div>
			<div class="sglist">
				<div v-for="(item,index) in list" :key="index" @click="goDetail(item.id)" class="sglist-item pointer">
					<div class="sglist-title">{{item.content}}</div>
					 
					<div v-if="item.imgsList" class="sglist-imglist">
						 
						<img v-for="(cc,ii) in item.imgsList" :key="ii" :src="cc+'.100x100.jpg'" class="sglist-imglist-img" />
						 
					</div>
					<div class="sglist-ft">
						<div class="sglist-ft-love">{{item.love_num}}</div>
						<div class="sglist-ft-cm">{{item.comment_num}}</div>
						<div class="sglist-ft-div">{{item.view_num}}</div>
					</div> 
				</div>
			</div>
			
			<div v-if="per_page>0" class="loadMore" @click="getList()">加载更多</div>
		</div>
	`
})