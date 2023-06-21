Vue.component("shop-detail",{
	props:{
		shopid:0
	},
	data:function(){
		return {
			shop:{},
			zzimgsdata:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				dataType:"json",
				url:"/module.php?m=flk_shop&a=detail&ajax=1&shopid="+this.shopid,
				success:function(res){
					that.shop=res.data.shop;
					that.zzimgsdata=res.data.zzimgsdata;
				}
			})
		}
	},
	template:`
		<div class="row-box">
			<div class="row-item-text ">
				<div class="row-item-icon icon-location_light"></div>
				<div class="flex-1">{{shop.address}}</div>
			</div>
			<div class="row-item-text ">
				<div class="row-item-icon icon-time"></div>
				<div class="flex-1">{{shop.yystart}}-{{shop.yyend}}</div>
			</div>
			<div class="row-item-text ">
				<div class="row-item-icon icon-selection"></div>
				<div class="flex-1">
					<div class="cl1">证件信息</div>
		
				</div>
			</div>
			<div class="sglist-imglist mgb-10">
				<img v-for="(item,index) in zzimgsdata" :key="index" class="sglist-imglist-img" :src="item+'.100x100.jpg'">
		
			</div>
			<div class="row-item-text ">
				<div class="row-item-icon icon-read"></div>
				<div class="flex-1">公司简介</div>
			</div>
			<div v-html="shop.content" class="d-content">
				 
			</div>
		</div>
	`
})