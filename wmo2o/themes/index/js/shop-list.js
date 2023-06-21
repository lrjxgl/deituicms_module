Vue.component("shop-list",{
	props:{
		shoplist:{},
		hotvip:0
	},
	data:function(){
		return {
			list:{}
		}
	},
	created:function(){
		this.list=this.shoplist; 
	},
	watch:{
		shoplist:function(n,o){
			this.list=n;
		}
	},
	methods:{
		goShop:function(shopid){
		 
			window.location="/module.php?m=wmo2o_shop&hotvip="+this.hotvip+"&shopid="+shopid
		}
	},
	template:`
		<div >
			<div @click="goShop(item.shopid)" v-for="(item,index) in list" :key="index" class="shopItem">
				<img :src="item.imgurl+'.100x100.jpg'"  class="shopItem-img" />
				<div class="flex-1">
					<div class="shopItem-title">{{item.title}}</div>
					<div class="flex mgb-5">
						
						<div class="shopItem-ratybox"><sky-raty label="" class="scalem2" len="5" mod="2" readonly="1" :grade="item.raty_grade"></sky-raty></div>
						<div class="f12 cl2 mgr-5">月售</div>
						<div class="f12 cl2">{{item.month_buy_num}}</div>
						<div class="flex-1"></div>
						<div class="mgr-10 cl2 f12">{{item.distance}}</div>
						<div class="f12cl2 ">{{item.sendtime}}</div>
					</div>
					<div class="flex flex-ai-center mgb-5">
						<div class="f12 cl2 mgr-10">起送 ￥{{item.send_startprice}}</div>
						<div class="f12 cl2 mgr-10">配送￥{{item.express_money}}</div>
						<div class="f12 cl2">人均￥{{item.avg_price}}</div>
						<div class="flex-1"></div>
						<!--<div class="shopItem-zsbtn">专送</div>-->
					</div>
					
					<div v-if="item.coupons" class="flex mgb-5 flex-ai-center">
						<div v-for="(cc,cckey) in item.coupons" :key="cckey" class="shopItem-mbtn">{{cc.title}}</div>
						 
					</div>
					<div class="flex" >
						<img v-for="(p,pi) in item.prolist" :key="pi" mode="widthFix" :src="p.imgurl+'.100x100.jpg'" class="wh-60 mgb-5" />
						 
					</div>
				</div>
			</div>
		</div>
	`
})