var touch=new vueTouch();
	touch.inMove=true;
new Vue ({
		el:"#App",
		data: function() {
			return {
				pageLoad:false,
				shop: {},
				list: {},
			 
				catList:{},
				catid: 0,
				ksShow: false,
				ksproduct: [],
				ksList: [],
				ksList2: [],
				ksid: 0,
				ksid1: 0,
				ksid2: 0,
				headerMoreClass: "",
				type:"",
				rpX:0,
				orpX:0,
				pinList:{},
				cscs:{},
				csc_num:0,
				shopFavClass:"",
				catClass:"",
				cat_label:"全部商品",
				per_page:0,
				isFirst:true,
				cart:{}
			}
		},
		created: function() {
			this.getPage();
			this.getList();
			this.getPin();
		},
		methods: {
			rpStart:function(e){
				touch.inMove=true;
				touch.inTouch=true;
				touch.touchstart(e);
				this.orpX=this.rpX;
			},
			swipeLeft:function(e){
				var rpXx=this.orpX+ (e.touches[0].clientX-touch.x);
				if(document.querySelector("#rpEnd").getBoundingClientRect().x<300){
						return ;
				} 
				this.rpX =rpXx;
				
			},
			swipeRight:function(e){
				var rpXx=this.orpX+ (e.touches[0].clientX-touch.x);
				if(rpXx>0){
					this.rpX=0;
					return false;
				}
				this.rpX =rpXx;
			},
			rpEnd:function(e){
				touch.inMove=true;
				touch.inTouch=true;
				return ;
				switch(touch.touchend(e)){
					case "swipeLeft":
						this.swipeLeft(e);
						break;
					case "swipeRight":
						this.swipeRight(e);
						break;
					default:
						console.log(touch.touchend(e))
						break;
				}
			},
			rpMove:function(e){
				touch.inMove=true;
				touch.inTouch=true;
				switch(touch.touchend(e)){
					case "swipeLeft":
						this.swipeLeft(e);
						break;
					case "swipeRight":
						this.swipeRight(e);
						break;
					
				}
			},
			backTop:function(){
				$(window).scrollTop(0); 
			},
			gourl:function(url){
				uni.navigateTo({
					url:url
				})
			},
			showCategory:function(){
				this.catClass="flex-col"
			},
			headerMoreShow: function() {
				this.headerMoreClass = 'flex-col';
			},
			headerMoreHide: function() {
				this.headerMoreClass = '';
			},
			setProType:function(type){
				this.type=type;
				this.isFirst=true;
				this.per_page=0;
				this.getList();
			},
			goOrder: function() {
				window.location="/module.php?m=csc_order&a=confirm&shopid="+shopid;
			},
			goGuest: function() {
				window.location="/module.php?m=csc_guest&a=user&shopid="+shopid;

			},
			goProduct: function(id) {
				window.location="/module.php?m=csc_product&a=show&id="+id+"&shopid="+shopid

			},
			getPin:function(){
				var that = this;
				$.ajax({
					url: "/module.php?m=csc_shop&a=recommend&ajax=1&shopid=" + shopid,
					dataType:"json",
					success: function(res) {
						that.pinList= res.data.list;
 
					}
				});
			},
			getPage: function() {
				var that = this;
				$.ajax({
					url:   "/module.php?m=csc_shop&ajax=1&shopid=" + shopid,
					dataType:"json",
					success: function(res) {
					 
						that.csc_num=res.data.csc_num;
						that.shop = res.data.shop;
						if(res.data.isfav==1){
							that.shopFavClass="btn-fav-active";
						}
						that.pageLoad = true;
						
					}
				});
			},
			getList: function() {
				var that = this;
				if(this.per_page==0 && !this.isFirst){
					return false;
				}
				$.ajax({
					url:  "/module.php?m=csc_product&ajax=1&shopid=" + shopid,
					data: {
						catid: that.catid,
						type:that.type,
						per_page:that.per_page
					},
					dataType: "json",
					success: function(res) {
						if(res.error){
							return false;
						}
						
						if(that.isFirst){
							that.catList=res.data.catList;
							that.list = res.data.list;
						}else{
							for(var i in res.data.list){
								that.list.push(res.data.list[i]);
							}
						}
						that.cart={
							cart_amount:res.data.cart_amount,
							cart_total_money:res.data.cart_total_money,
							cart_total_num:res.data.cart_total_num,
							express_money:res.data.express_money
						};	
						that.per_page=res.data.per_page;
					}
				})
			},
			 
	 
			favShopToggle:function(shopid){
				var that=this;
				$.ajax({
					url:"/index.php?m=fav&a=toggle&ajax=1",
					data:{
						tablename:"mod_csc_shop",
						objectid:shopid
					},
					dataType:"json",
					success:function(res){
						if(res.error){
							uni.showToast({
								title:res.message
							})
						}
						
						if(res.data=="add"){
							that.shopFavClass='btn-fav-active';
						}else{
							that.shopFavClass='';
						}
					}
				})
			},
	 
			setCat: function(catid,cat) {
				this.catClass="";
				if(catid){
					this.catid=cat.catid;
					this.cat_label=cat.title;
				}else{
					this.catid=0;
					this.cat_label="全部商品";
				}
				this.per_page=0;
				this.isFirst=true;
				this.getList();
			}
			 
		}
	})