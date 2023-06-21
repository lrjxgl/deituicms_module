Vue.component("product-detail",{
	props:{
		shopid:0,
		productid:0
	},
	data:function(){
		return {
			data:{},
			globalData:globalData, 
			shareClass:"",
			pageLoad:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=mmjz_product&a=show&ajax=1",
				dataType:"json",
				data:{
					id:this.productid
				},
				success:function(res){
					that.data=res.data.data;
					that.pageLoad=true;
				}
			})
		},
		goShop:function(){
			globalData.pageTab="index"
		}
	},
	template:`
		<div>
			<div class="header-fixtop-right">
				<div @click="shareClass='flex-col'" class="header-fixtop-icon pointer cl2 icon-share"></div>
			</div>
			<div :class="shareClass"  class="modal-group">
				<div @click="shareClass=''"  class="modal-mask" style="background-color: #000; opacity: 0.8;"></div>
				<div style="position: fixed;right: 0px;top: 30px; z-index: 9999;">
					<img src="/static/images/wx_guide.png" style="width: 200px;" />
				</div>
			</div>
			<div v-if="pageLoad">
			 
				<div  @click="goShop" class="header-back-fixed  cl2"></div>
				<div   class="main-body">
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
		</div>
	`
})