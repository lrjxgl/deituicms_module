Vue.component("shop-product",{
	
	props:{
		shopid:"",
		dclass:""
	}, 
	data: function() {
		return {
			page: "product",
			pageLoad: false,
			list: {},
			showCart:false, 
			shop: {},
			sideClass: "",
			catList: {},
			catid: 0,
			per_page: 0,
			isFirst: true,
			cart: {},
			ksShow: false,
			ksproduct: [],
			ksList: [],
			ksList2: [],
			ksid: 0,
			ksid1: 0,
			ksid2: 0,
			orderby: "",
			esTop:160,
			sideClass:""
		}
	},
	created: function(ops) {
		 
		 
		this.getList();
		this.statCart();
		
		
	},
	watch:{
		dclass:function(n,o){
			console.log(n)
			this.sideClass=n;
		}
	},
	methods: {
		setCart:function(e){
			if(e.action=="update"){
				this.statCart()
			}else if(e.action=="close"){
				this.showCart=false;
			}
		},
		goBack: function() {
			uni.navigateBack()
		},
		 
		 
		setCat: function(catid) {
			this.catid = catid;
			this.per_page = 0;
			this.isFirst = true;
			 
			this.getList();
		},
		setOrder: function(o) {
			this.orderby = o;
			this.isFirst = true;
			this.per_page = 0;
			this.getList();
		},
		getList: function() {
			var that = this;
			if (this.per_page == 0 && !this.isFirst) {
				return false;
			}
			$.ajax({
				dataType: "json",
				url:  "/module.php?m=flk_product&ajax=1&shopid=" + this.shopid,
				data: {
					catid: that.catid,
					type: that.type,
					per_page: that.per_page,
					orderby: this.orderby,
				},
				dataType: "json",
				success: function(res) {
					if (res.error) {
						return false;
					}
		
					if (that.isFirst) {
						that.catList = res.data.catList;
						that.list = res.data.list;
						that.pageLoad=true;
						setTimeout(function(){
							that.esTop=$("#esDot2").offset().top;
						},30); 
					} else {
						for (var i in res.data.list) {
							that.list.push(res.data.list[i]);
						}
					}
					that.per_page = res.data.per_page;
					that.isFirst = false;
				}
			})
		},
		goOrder: function() {
			window.location = "/module.php?m=flk_order&a=confirm&shopid=" + shopid;
		},
		goGuest: function() {
			window.location = "/module.php?m=flk_guest&a=user&shopid=" + shopid;
		
		},
		goProduct: function(id) {
			window.location = "/module.php?m=flk_product&a=show&id=" + id + "&shopid=" + shopid
		
		},
		 
		/***购物车处理***/
		addCart: function(id, ksid) {
			var that = this;
			var id = id;
			var ksid = ksid == undefined ? 0 : ksid;
			var amount = 1;
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",
				success: function(res) {
					if (res.error == 1000) {
						window.location = "/index.php?m=login"
						return false;
					}
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					for (var i = 0; i < that.list.length; i++) {
						if (that.list[i].id == id) {
							if (res.data.amount > 0) {
								that.list[i].incart = 1;
							}
							that.list[i].cart_amount = res.data.amount;
							break;
						}
					}
		
				}
			})
		},
		plusCart: function(id, amount, ksid) {
			var that = this;
			var id = id;
			var amount = amount;
			var ksid = ksid == undefined ? 0 : ksid;
			amount++;
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",
		
				success: function(res) {
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					for (var i = 0; i < that.list.length; i++) {
						if (that.list[i].id == id) {
		
							that.list[i].cart_amount = res.data.amount;
							break;
						}
					}
		
		
				}
			})
		},
		minusCart: function(id, amount, ksid) {
			var that = this;
			var id = id;
			var amount = amount;
			var ksid = ksid == undefined ? 0 : ksid;
			amount--;
			var isdelete = 0;
			if (amount == 0) {
				isdelete = 1
			}
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid,
					isdelete: isdelete
				},
				method: 'GET',
				dataType: "json",
		
				success: function(res) {
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					for (var i = 0; i < that.list.length; i++) {
						if (that.list[i].id == id) {
							if (res.data.amount == 0) {
								that.list[i].incart = 0;
							}
							that.list[i].cart_amount = res.data.amount;
							break;
						}
					}
		
		
				}
			})
		},
		
		//规格操作
		ksBox: function(id) {
			var that = this;
			$.ajax({
				url: "/module.php?m=flk_product_ks&ajax=1&productid=" + id,
				dataType: "json",
				success: function(res) {
					that.ksShow = true;
					that.ksproduct = res.data.product;
					that.ksList = res.data.ksList;
					that.ksList2 = res.data.ksList2;
					that.ksid1 = res.data.ksid;
					that.ksid = res.data.ksid;
				}
			})
		},
		ksHide: function() {
			this.ksid = 0;
			this.ksShow = false;
		},
		ks1: function(id) {
			var that = this;
			$.ajax({
				url: "/module.php?m=flk_product_ks&a=sizeList&ajax=1&id=" + id,
				dataType: "json",
				success: function(res) {
					that.ksid1 = res.data.ksid;
					that.ksid = res.data.ksid;
					that.ksproduct = res.data.product;
					that.ksList2 = res.data.ksList2;
				}
			})
		},
		ks2: function(id) {
			var that = this;
			that.ksid = id;
			$.ajax({
				url: "/module.php?m=flk_product_ks&a=get&ajax=1&id=" + id,
				dataType: "json",
				success: function(res) {
		
					that.ksproduct = res.data.product;
		
				}
			})
		},
		ksAddCart: function(id) {
			var that = this;
			var id = id;
			var ksid = that.ksid;
			var amount = 1;
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",
				success: function(res) {
					if (res.error == 1000) {
						window.location = "/index.php?m=login"
						return false;
					}
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					that.ksproduct.incart = 1;
					that.ksproduct.cart_amount = res.data.amount;
				}
			})
		},
		ksPlusCart: function(id, amount) {
			var that = this;
			var id = id;
			var amount = amount;
			var ksid = that.ksid;
			amount++;
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",
		
				success: function(res) {
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					that.ksproduct.cart_amount = res.data.amount;
				}
			})
		},
		ksMinusCart: function(id, amount) {
			var that = this;
			var id = id;
			var amount = amount;
			var ksid = that.ksid;
			amount--;
			var isdelete = 0;
			if (amount == 0) {
				isdelete = 1
			}
			$.ajax({
				url: '/module.php?m=flk_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid,
					isdelete: isdelete
				},
				method: 'GET',
				dataType: "json",
		
				success: function(res) {
					if (res.error) {
						skyToast(res.message);
						return false;
					}
					that.statCart();
					if (res.data.amount == 0) {
						that.ksproduct.cart_amount = 0;
						that.ksproduct.incart = 0;
					} else {
						that.ksproduct.cart_amount = res.data.amount;
					}
		
				}
			})
		},
		statCart: function() {
			var that = this;
			$.ajax({
				dataType: "json",
				url: "/module.php?m=flk_cart&a=statshop&ajax=1",
				data: {
					shopid: this.shopid
				},
				success: function(res) {
					if(res.error){
						that.cart = {
							cart_amount: 0,
							cart_total_money: 0,
							cart_total_num: 0,
							express_money: 0
						};
					}else{
						that.cart = {
							cart_amount: res.data.cart_amount,
							cart_total_money: res.data.cart_total_money,
							cart_total_num: res.data.cart_total_num,
							express_money: res.data.express_money
						};
					}
				}
			})
		}
	},
	template:`
		<div>
				<div id="esDot2"></div>
				<div v-if="pageLoad">
					<div class="">
						<div :class="sideClass" :style="{top:esTop+'px'}" class="side">
							<div @click="setCat(0)" :class="{'side-menu-active':catid==0}" class="side-menu">推荐</div>
							<div @click="setCat(item.catid)" :class="{'side-menu-active':catid==item.catid}" v-for="(item,index) in catList"
							 :key="index" class="side-menu">
								{{item.title}}
							</div>
						</div>
						<div class="flex-1 main">
							<div class="tabs-border">
								<div @click="setOrder('')" :class="{'tabs-border-active':orderby==''}" class="tabs-border-item">综合</div>
								<div @click="setOrder('buy_num')" :class="{'tabs-border-active':orderby=='buy_num'}" class="flex flex-center tabs-border-item">
									<span class="mgr-5">销量</span>
									<span class="iconfont icon-godown"></span>
								</div>
								<div @click="setOrder('price')" :class="{'tabs-border-active':orderby=='price'}" class="tabs-border-item flex flex-center ">
									<span class="mgr-5">价格</span>
									<span class="iconfont icon-godown"></span>
								</div>
							</div>
							<div class="flexlist">
								<div class="flexlist-item" v-for="(item,index) in list" :key="index">
									<img @click="goProduct(item.id)" class="flexlist-img" :src="item.imgurl+'.100x100.jpg'" />
									<div class="flex-1">
										<div @click="goProduct(item.id)" class="flexlist-title pointer">{{item.title}}</div>
										<div class="flex flex-ai-center mgb-5">
											<div class="cl-money f12">￥</div>
											<div class="cl-money f14 mgr-5">{{item.price}}</div>
											<div class="flex-1"></div>
											<div class="cl3 f10">已售{{item.buy_num}}件</div>
		
		
										</div>
										<div class="flex">
											<div class="flex-1"></div>
											<div v-if="item.isksid>0">
												<div @click="ksBox(item.id)" class="btn-small btn-outline-success">选规格</div>
											</div>
											<div class="pdb-5" v-else>
		
												<div v-if="item.incart" class="numbox prolist-numbox">
													<div @click="minusCart(item.id,item.cart_amount)" class="numbox-minus">-</div>
													<input type="text" name="amount" :value="item.cart_amount" class="numbox-num" />
													<div @click="plusCart(item.id,item.cart_amount)" class="numbox-plus">+</div>
												</div>
												<div @click="addCart(item.id)" class="btn-buy   iconfont icon-cart" v-else></div>
											</div>
										</div>
									</div>
		
								</div>
							</div>
							<div class="loadMore" v-if="per_page>0" @click="getList()">加载更多</div>
		
						</div>
					</div>
					<div class="footer-row"></div>
					<div class="footerBox pd-5">
						<div @click="goGuest" class=" w60 pointer flex-center mgr-5">
							<div class="iconfont icon-service cl-primary f18"></div>
							<div class="f12">客服</div>
						</div>
						<div v-if="cart.cart_total_num==0" class="flex-1 flex">
							<div class="cl2">暂无商品，快去选购吧</div>
						</div>
						<div @click="showCart=true" class="flex-1" v-if="cart.cart_total_num>0">
							<div class="cl-money">￥{{cart.cart_total_money}}</div>
							<div>运费金额 ￥{{cart.express_money}}</div>
						</div>
						<div v-if="cart.cart_total_num>0" class="btn" @click="goOrder">去结算</div>
					</div>
				</div>
				<!--规格-->
				<div v-if="ksShow">
					<div class="modal-mask" @click="ksHide"></div>
					<div class="modal">
						<div class="modal-header">
							<div class="modal-title">选择款式</div>
							<div class="modal-close icon-close" @click="ksHide"></div>
						</div>
						<div class="modal-body pdt-10">
							<div class="ksBox mgb-10">
								<div class="kslist mgb-10">
									<div class="kslist-label">{{ksproduct.ks_label_name}}</div>
									<div @click="ks1(item.id)" v-bind:class="{'kslist-active':item.id==ksid1}" class="kslist-item" v-for="(item,index) in ksList"
									 :key="index">{{item.title}}</div>
								</div>
								<div class="kslist">
									<div class="kslist-label">{{ksproduct.ks_label_size}}</div>
									<div class="kslist-item" @click="ks2(item.id)" v-bind:class="{'kslist-active':item.id==ksid}" v-for="(item,index) in ksList2"
									 :key="index">{{item.size}}</div>
								</div>
							</div>
							<div class="flex">
								<div class="cl2 mgr-10 mgl-10">价格</div>
								<div class="cl-money">￥{{ksproduct.price}}</div>
								<div class="flex-1"></div>
								<div v-if="ksproduct.incart" class="numbox prolist-numbox">
									<div @click="ksMinusCart(ksproduct.id,ksproduct.cart_amount)" class="numbox-minus">-</div>
									<input type="text" name="amount" :value="ksproduct.cart_amount" class="numbox-num" />
									<div @click="ksPlusCart(ksproduct.id,ksproduct.cart_amount)" class="numbox-plus">+</div>
								</div>
								<div @click="ksAddCart(ksproduct.id)" class="btn-buy" v-else>买</div>
							</div>
						</div>
					</div>
				</div>
				<shop-cart v-if="showCart" @call-parent="setCart" :shopid="shopid"></shop-cart>
			</div>
	`
})