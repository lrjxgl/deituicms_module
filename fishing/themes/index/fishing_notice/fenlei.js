Vue.component('page-fenlei',{
 
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
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fenlei_guest&a=index&ajax=1",
				data:{
					type:this.type
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
				url:"/module.php?m=fenlei_guest&a=index&ajax=1",
				data:{
					type:this.type,
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
					 
				}
			})
		},
		goDetail:function(item){
			window.location="/module.php?m=fenlei_guest&a=user&id="+item.objectid+"&touserid="+item.touserid
		} 
	},
	template:`
		<div>
			 
			<div v-if="list.length==0" class="emptyData">暂无消息</div>
			<div @click="goDetail(item)" v-for="(item,index) in list" :key="index" class="row-box flex mgb-5">
				<img class="flexlist-img" :src="item.to_user_head+'.100x100.jpg'" />
				<div class="flex-1">
					<div class="flex mgb-5">
						<div class="flex-1 f16 cl2">{{item.to_nickname}}</div>
						<div class="cl3 f12">{{item.timeago}}</div>
					</div>
					<div class="flexlist-desc">{{item.content}}</div>
				</div>
			</div>
		</div>
		
	`
})