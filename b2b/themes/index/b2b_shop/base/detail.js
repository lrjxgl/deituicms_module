Vue.component('shop-detail',{
	props:{
		shopid:0
	},
	data:function(){
		return {
			shop:{}
		}
	},
	created:function(){
		this.getPage()
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_shop&a=detail&ajax=1&shopid="+this.shopid,
				dataType:"json",
				success:function(res){
					that.shop=res.data.shop;
				}
			})
		}
		
	},
	"template":`
	<div class="main-body">
		<div class="row-box mgb-5">
	
			<div class="row-item-text">
				<div class="row-item-icon icon-shop"></div>
				<div class="flex-1">{{shop.shopname}}</div>
			</div>
	
			<div class="row-item-text">
				<div class="row-item-icon icon-profile_light"></div>
				<div class="mgr-10">{{shop.nickname}}</div>
				<a :href="'tel:'+shop.telephone" class="flex-1">{{shop.telephone}}</a>
			</div>
			<div class="row-item-text ">
				<div class="row-item-icon icon-location_light"></div>
				<div class="flex-1">{{shop.address}}</div>
			</div>
			<div class="flex">
				<img :src="'https://api.map.baidu.com/staticimage/v2?ak=F73283d678ec76619500152b1a0835c0&mcode=666666&center='+shop.lng+','+shop.lat+'&width=300&height=200&zoom=11'" />
			</div>
	
	
	
	
		</div>
		<div class="row-box mgb-5">
			<div class="row-item-text mgb-10">
				<div class="row-item-icon icon-read"></div>
				<div class="flex-1">公司介绍</div>
			</div>
			<div v-html="shop.content" class="d-content">
			 
			</div>
		</div>
	</div>	
	`
})