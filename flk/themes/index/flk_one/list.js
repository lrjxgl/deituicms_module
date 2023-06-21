Vue.component("page-list",{
	props:{
		
	},
	data:function(){
		return {
			tab:"doing",
			list:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.tab
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		setTab:function(t){
			this.tab=t;
			this.getList();
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_one&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.tab
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goDetail:function(id){
			console.log(id)
			this.$emit("call-parent",{
				page:"show",
				id:id
			});
		}
	},
	template:`
		<div>
			<div class="header">
				<div class="header-back"></div>
				<div class="header-title">秒杀精选</div>
			</div>
			<div class="header-row"></div>
			<div class="main-body">
				<div class="tabs-border">
					<div @click="setTab('doing')" :class="{'tabs-border-active':tab=='doing'}" class="tabs-border-item">疯抢中</div>
					<div @click="setTab('done')"  :class="{'tabs-border-active':tab=='done'}" class="tabs-border-item">往期活动</div>
				</div>
				<div class="flexlist-item flex-ai-center" v-for="(item,index) in list" :key="index">
					<img :src="item.imgurl+'.100x100.jpg'" class="flexlist-img" />
					<div class="flex-1">
						<div class="flexlist-title">{{item.title}}</div>
						<div class="flex mgb-5">
							<div class="mgr-5 cl2 f12">价格</div>
							
							<div class="cl-money">￥{{item.price}}</div>
						</div>
						<div class="flex cl2 f12">
							{{item.one_etime}}
						</div>
					</div>
					<div @click="goDetail(item.id)" class="btn-mini btn-outline-primary">马上抢购</div>
				</div>
			</div>
		</div>
	`
})