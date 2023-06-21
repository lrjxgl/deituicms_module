Vue.component("shop-cart",{
	props: {
		shopid: 0
	},
	data: function() {
		return {
			list: [],
			total_num: 0,
			total_money: 0
		}
	},
	watch:{
		show:function(n,o){
			if(n==true){
				this.getPage();
			}
		}
	},
	created: function() {
		this.getPage();
		 
	},
	methods: {
		close:function(){
			this.$emit("call-parent",{action:"close"})
		},
		getPage: function() {
			var that = this;
			$.ajax({
				dataType:"json",
				url:  "/module.php?m=flk_cart&a=getbyshop&ajax=1",
				unLogin: true,
				data: {
					shopid: this.shopid
				},
				success: function(res) {
					if (res.error) {
						return false;
					}
					that.list = res.data.list;
					that.total_num = res.data.total_num;
					that.total_money = res.data.total_money;
				}
			})
		},
		plusCart: function(id, amount, ksid) {
			var that = this;
			var productid = id;
			var amount = amount;
			var ksid = ksid == undefined ? 0 : ksid;
			amount++;
			$.ajax({
				dataType:"json",
				url:'/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: productid,
					amount: amount,
					ksid: ksid,
					 
				},
				success: function(res) {
					that.$emit("call-parent",{action:"update"})
					that.getPage();
				}
			})
		},
		minusCart: function(id, amount, ksid) {
			var that = this;
			var productid = id;
			var amount = amount;
			var ksid = ksid == undefined ? 0 : ksid;
			amount--;
			var isdelete = 0;
			if (amount == 0) {
				isdelete = 1
			}
			$.ajax({
				dataType:"json",
				url:  '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: productid,
					amount: amount,
					ksid: ksid,
					isdelete: isdelete
				},
				success: function(res) {
					that.$emit("call-parent",{action:"update"})
					that.getPage();
				}
			})
		},
	},
	template:`
		<div>
			<div class="fixCartBox-bg pointer"  @click="close" ></div>
			<scroll-div scroll-y="true" class="fixCartBox">
				<div class="flex">
					<div class="flex-1"></div>
					<div @click="close" class="pd-5 pointer">关闭</div>
				</div>
				<div class="flexlist">
					<div class="flexlist-item" v-for="(item,index) in list" :key="index">
						<image :src="item.imgurl+'.100x100.jpg'" mode="widthFix" class="flexlist-img"></image>
						<div class="flex-1">
							<div class="flexlist-title">{{item.title}}</div>
							<div class="flexlist-row">
								<div class="">{{item.ks_title}}</div>
								<div class="cl-money">
									￥{{item.price}}
								</div>
								<div class="flex-1"></div>
								<div class="numbox">
									<div @click="minusCart(item.productid,item.amount,item.ksid)" class="numbox-minus">-</div>
									<input type="text" name="amount" :value="item.amount" class="numbox-num" />
									<div @click="plusCart(item.productid,item.amount,item.ksid)" class="numbox-plus">+</div>
								</div>
							</div>
		
						</div>
					</div>
				</div>
				<div class="pd-10 flex">
					<div class="flex-1">
						共{{total_num}}件，总{{total_money}}元
					</div>
		
				</div>
			</scroll-div>
		</div>
	`
})
 
 
