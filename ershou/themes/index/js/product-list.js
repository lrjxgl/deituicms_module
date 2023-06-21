Vue.component("product-list",{
	props:{
		dlist:[]
	},
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		console.log(this.dlist)
		this.list=this.dlist;
	},
	watch:{
		dlist:function(n,o){
			this.list=n;
		}
	},
	methods:{
		goProduct:function(productid){
			window.location="/module.php?m=ershou_product&a=show&productid="+productid
		}
	},
	template:`
		<div>
			<div class="flex">
				<div class="flex-1 mgr-10">
					<template v-for="(item,index) in list" :key="index">
						<div @click="goProduct(item.productid)" v-if="index%2==0"  class="sglist-item">
							<img :src="item.imgurl+'.100x100.jpg'" class="sglist-img" />
							<div class="sglist-title">{{item.description}}</div>
							<div class="flex">
								<div class="cl-money">￥{{item.price}}</div>
								<div class="mgl-10 cl3">{{item.love_num}}人想要</div>
							</div>
						</div>
					</template>
					
					 
				</div>
				<div class="flex-1">
					<template v-for="(item,index) in list" :key="index">
						<div  @click="goProduct(item.productid)"  v-if="index%2==1"  class="sglist-item">
							<img :src="item.imgurl+'.100x100.jpg'" class="sglist-img" />
							<div class="sglist-title">{{item.description}}</div>
							<div class="flex">
								<div class="cl-money">￥{{item.price}}</div>
								<div class="mgl-10 cl3">{{item.love_num}}人想要</div>
							</div>
						</div>
					</template>
				</div>
			</div>
		</div>
	`
})