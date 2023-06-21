Vue.component("index-blog",{
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
				url:"/module.php?m=jdo2o_blog&a=list&ajax=1&placeid="+placeid,
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.list=res.data.list;
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=jdo2o_blog&a=show&id="+id;
		}
	},
	template:`
		<div>
		<div v-for="(item,index) in  list" :key="index" @click="goBlog(item.id)" class="sglist-item">
			
			<div class="sglist-title">{{item.content}}</div>
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
var blogApp=new Vue({
	el:"#blogApp"
})