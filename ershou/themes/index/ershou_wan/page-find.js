Vue.component("page-find",{
	data:function(){
		return {
			groupList:[],
			id:0,
			list:[],
			page:""
		}
	},
	created:function(){
		var that=this;
		this.getPage();
 
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group&ajax=1",
				dataType:"json",
				success:function(res){
					that.groupList=res.data.data;
					that.list=res.data.topiclist; 
					
				}
			})
		},
		goGroup:function(gid){
			window.location="/module.php?m=group&a=show&gid="+gid
		}
	},
	template:`
	<div>
		
		
		<div class="row-box mgb-10">
			 <template  v-for="(item,index) in groupList" :key="index">
			 	<div @click="goGroup(item.gid)" class="flex flex-ai-center mgb-10" v-if="index<2" >
			 		<img :src="item.glogo+'.100x100.jpg'" class="w50 bd-radius-10 mgr-5" />
			 		<div class="flex-1">
			 			<div class="mgb-5 fw-600">{{item.title.substr(0,12)}}</div>
			 			<div class="cl2 f12">{{item.user_num}}人在讨论</div>
			 		</div>
					<div class="btn-small btn-light">+加入</div>
			 	</div>
			 </template>
		</div>
	</div>
	`
})