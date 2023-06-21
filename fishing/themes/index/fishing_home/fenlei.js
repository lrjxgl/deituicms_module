Vue.component("page-fenlei",{
 
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
			window.location="/module.php?m=fenlei&a=show&id="+id
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fenlei&a=search&ajax=1",
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
				url:"/module.php?m=fenlei&a=search&ajax=1",
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
					<div class="flex mgb-5">
						<div v-if="item.hb_on" class="bc-red mgr-5">红</div>
						<div class="f14">{{item.title}}</div>
					</div>						 								 
					<div v-if="item.money>0" class="flexlist-row">
						<div class="cl-money">￥{{item.money}}</div>
					</div>						 
					<div class="sglist-desc">{{item.description}}</div>
					<div class="sglist-imglist">
								 
						<img v-for="(img,imgIndex) in item.imgsdata" :key="imgIndex" :src="img+'.100x100.jpg'" class="sglist-imglist-img" />
					</div>
					<div class="cl3 f12">发布于{{item.createtime}}</div>
				</div>
			</div>
			
			<div v-if="per_page>0" class="loadMore" @click="getList()">加载更多</div>
		</div>
	`
})