Vue.component('product-show', {
	props: {
		productid: 0
	},
	data: function() {
		return {
			shop: {},
			data: {},
			globalData:globalData,
			pageLoad:false
		}
	},
	created: function() {
		this.getPage()
	},
	methods: {
		getPage: function() {
			var that = this;
			$.ajax({
				url: "/module.php?m=shopsite_product&a=show&ajax=1&id=" + this.globalData.productid,
				data:{
					shopid:this.globalData.shopid
				},
				dataType: "json",
				success: function(res) {
					that.data = res.data.data;
					that.pageLoad=true;
					
				}
			})
		}

	},
	"template": `
<div>	
 
	<div @click="globalData.pageTab='index'" class="header-back-fixed"></div>
	<div v-if="pageLoad" class="main-body">
		<img :src="data.imgurl+'.middle.jpg'"  class="d-img" />
		<div class="row-box mgb-5">
			<div class="d-title">{{data.title}}</div>
			<div class="flex flex-ai-center">
				<div class="mgr-5">价格</div>
				<div class="cl-money">￥{{data.price}}</div>
			</div>
		</div>
		<div class="row-box mgb-5">
			 
			<div v-html="data.content" class="d-content">
			 
			</div>
		</div>
	</div>
</div>		
	`
})
