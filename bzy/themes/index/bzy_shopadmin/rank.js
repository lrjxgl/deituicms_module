Vue.component("rank-list",{
	props:{
		eventid:0
	},
	data:function(){
		return {
			rankList:[],
			rankType:"day",
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=bzy_rank&ajax=1",
				dataType:"json",
				data:{
					eventid:this.eventid,
					rankType:this.rankType
				},
				success:function(res){
					that.rankList=res.data.list;
					
				}
			})
		},
		setRank:function(rankType){
			this.rankType=rankType;
			this.getPage();
		},
	},
	template:`
		<div>
			<div class="tabs-border">
				<div @click="setRank('day')" :class="rankType=='day'?'tabs-border-active':''" class="tabs-border-item">今日榜</div>
				<div @click="setRank('lastday')" :class="rankType=='lastday'?'tabs-border-active':''"  class="tabs-border-item">昨日榜</div>
				<div @click="setRank('all')" :class="rankType=='all'?'tabs-border-active':''"  class="tabs-border-item">总分榜</div>
			</div>
			 
			<div v-if="rankList.length==0" class="emptyData">暂无积分</div>
			<div v-else>
				<div class="flex ph-hd">
					<div class="ph-a">排行</div>
					<div class="ph-b">昵称</div>
					<div class="ph-c">积分</div>
				</div>
				
				<div v-for="(item,index) in rankList" :key="index" class="flex ph-con">
					<div  class="ph-a" :class="'phc'+index">{{index+1}}</div>
					<div  class="ph-b">{{item.nickname}}</div>
					<div  class="ph-c">{{item.grade}}</div>
				</div>
			</div>
		</div>
	`
})