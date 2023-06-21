Vue.component("page-news",{
	props:{
		gid:0
	},
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_news&a=home&ajax=1",
				dataType:"json",
				data:{
					gid:this.gid
				},
				success:function(res){
					that.list=res.data.list
				}
			})
		},
		goNews:function(id){
			window.location="/module.php?m=zupu_news&a=show&id="+id
		}
	},
	template:`
		<div>
			<div class="flexlist">
				<div  @click="goNews(item.id)"  v-for="(item,index) in list" :key="index" class="flexlist-item">
					<img class="flexlist-img" :src="item.imgurl+'.100x100.jpg'" />
					<div class="flex-1">
						<div class="flexlist-title">{{item.title}}</div>
						<div class="flexlist-desc">{{item.description}}</div>
					</div>
				</div>
			</div>
		</div>
	`
})