Vue.component("index-place",{
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=jdo2o_place&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.list=res.data.list;
				}
			})
		},
		goBlog:function(placeid){
			window.location="/module.php?m=jdo2o_place&a=show&placeid="+placeid;
		}
	},
	template:`
	<div>
		<div v-for="(item,index) in  list" :key="index" @click="goBlog(item.placeid)" class="sglist-item">
			<img v-if="item.imgurl!=''" class="sglist-img" :src="item.imgurl+'.small.jpg'" />
			<div class="sglist-title">{{item.title}}</div>
			<div class="sglist-desc">{{item.description}}</div>
			<div class="sglist-imglist">
				 
				<img v-for="(img,imgIndex) in item.imgslist" :key="imgIndex" :src="img+'.100x100.jpg'" class="sglist-imglist-img" />
				
			</div>
			<div class="sglist-ft">
				<div class="sglist-ft-love">{{item.love_num}}</div>
				<div class="sglist-ft-cm">{{item.comment_num}}</div>
				<div class="sglist-ft-view">{{item.view_num}}</div>
			</div> 
		</div>
	</div>
	`
});