Vue.component("head-more",{
	
	data:function(){
		return {
			fbox:false
		}
	},
	created:function(){
		var that=this;
		 
	},
	 
	methods:{
		 
	},
	template:`
	<div>
		<div @click="fbox=true" class="iconfont icon-more_light f20 mgr-10"></div>
		<div v-if="fbox">
			<div @click="fbox=false" class="modal-mask"></div>
			<div  style="position:fixed;z-index:99;left:0;right:0;bottom:0px;">
				<div class="flex" style="background-color:#fafafa;padding:20px;border-top-left-radius:20px;border-top-right-radius:20px;">
					<div gourl="/module.php?m=ershou_message" class="flex-1 flex-center">
						<div class="iconfont icon-message_light"></div>
						<div>消息</div>
					</div>
					<div gourl="/module.php?m=ershou_history" class="flex-1 flex-center">
						<div class="iconfont icon-message_light"></div>
						<div>足迹</div>
					</div>
					<div gourl="/index.php?m=kefu" class="flex-1 flex-center">
						<div class="iconfont icon-message_light"></div>
						<div>客服</div>
					</div>
					<div gourl="/index.php?m=guest" class="flex-1 flex-center">
						<div class="iconfont icon-message_light"></div>
						<div>反馈</div>
					</div>
				</div>
				<div @click="fbox=false" style="text-align:center;padding:20px;background-color:#fff;">取消</div>
			</div>
		</div>
	</div>
	`
})