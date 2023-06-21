Vue.component("shop-detail",{
	props:{
		shopid:0
	},
	data:function(){
		return {
			shop:{},
			zzimgsdata:[],
			certList:[]
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
				url:"/module.php?m=mmjz_shop&a=detail&ajax=1&shopid="+this.shopid,
				success:function(res){
					that.shop=res.data.shop;
					that.zzimgsdata=res.data.zzimgsdata;
					that.certList=res.data.certList;
				}
			})
		},
		goCert:function(item){
			var imgs=item.imgslist;
			js_thumb_index=0;
			if(js_thumb_swiper!=false){
				js_thumb_swiper.destroy();
				js_thumb_swiper=false;
			}
			$("#js-thumb-box .swiper-wrapper").html("");
			for(var i=0;i<imgs.length;i++){
			
				$("#js-thumb-box .swiper-wrapper").append('<div class="swiper-slide"><img src="'+imgs[i]+'" /></div>');
			}
			$("#js-thumb-box").show();
			js_thumb_swiper = new Swiper ('#js-thumb-swiper-container', {
			  loop: false,
			  pagination: {
			    el: '#js-thumb-swiper-pagination'
			  }
			})
			if(js_thumb_index!=0){
				js_thumb_swiper.slideTo(js_thumb_index,0); 	
			}
		},
		goReport:function(shopid){
			window.location="/module.php?m=mmjz_report&a=add&shopid="+shopid
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
			<div v-if="certList && certList.length>0">
				<div @click="goCert(item)" v-for="(item,index) in certList" :key="index" class="row-item">
					<div class="row-item-icon icon-selection"></div>
					<div class="row-item-title">{{item.title}}</div>
				</div>
			</div>
			<div @click="goReport(shop.shopid)" class="row-item ">
				<div class="row-item-icon icon-lightforbid"></div>
				<div class="flex-1">遇到问题，投诉商家</div>
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