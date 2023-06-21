Vue.component("head-search",{
	props:{
		dword:""
	},
	data:function(){
		return {
			keyword:"",
			inSearch:false
		}
	},
	created:function(){
		var that=this;
		this.keyword=this.dword; 
 
	},
	watch:{
		dword:function(){
			this.keyword=this.dword;
		}
	},
	methods:{
		search:function(){
			this.$emit("call-parent",this.keyword);
			this.inSearch=false;
			 
		} 
	},
	template:`
	<div class="flex-1 flex  flex-ai-center">
		<div class="header-search-box mgr-10">
			<div class="header-search-icon icon-search_light"></div>
			<input @focus="inSearch=true"   v-model="keyword" placeholder="搜索你想要的宝贝" class="header-search bd-radius-20" />
			<div v-if="inSearch" @click="inSearch=false" style="position:absolute;padding:10px;right:0;top:-5px;" class="iconfont icon-close"></div>
		</div>
		<div @click="search" class="mgl-5 mgr-5 fw-600" v-if="inSearch">搜索</div> 
	</div>
	`
})