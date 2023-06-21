Vue.component("page-guest",{
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
				url:"/module.php?m=sjsj_guest&ajax=1",
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
				url:"/module.php?m=sjsj_guest&ajax=1",
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
		goGuest:function(item){
			window.location="/module.php?m=sjsj_guest&a=user&newsid="+item.objectid+"&touserid="+item.touserid
		}
	},
	template:`
	<div>
		<div @click="goGuest(item)" v-for="(item,index) in list" :key="index" class="flexlist-item">
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