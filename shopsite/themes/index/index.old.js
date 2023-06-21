var touch=new vueTouch();
	touch.inMove=true;
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{},
			pageLoad:true,
			catList:{},
			catid: 0,
			catid:0,
			type:"",
			per_page:0,
			isFirst:true,
			page:"product",
			ratyPage:{},
			detailPage:{},
			ksShow:false,
			ksproduct:[],
			ksList:[],
			ksList2:[],
			ksid:0,
			ksid1:0,
			ksid2:0,
			rpX:0,
			orpX:0,
			product:{},
			catClass:"",
			cat_label:"全部商品",
		}
	},
	created:function(){
		this.getProduct();
		
	},
	methods:{
		rpStart:function(e){
			touch.inMove=true;
			touch.inTouch=true;
			touch.touchstart(e);
			this.orpX=this.rpX;
		},
		swipeLeft:function(e){
			var rpXx=this.orpX+ (e.touches[0].clientX-touch.x);
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
		showCategory:function(){
			this.catClass="flex-col"
		},
		goGuest:function(){
			window.location="/module.php?m=b2b_guest&a=user&shopid="+shopid;
		},
		setProType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getProduct();
		},
		backTop:function(){
			$(window).scrollTop(0); 
		},
		goProduct:function(id){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_product&a=show&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					that.product=res.data.data;
				}
			})
		},
		getProduct:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_product&ajax=1&shopid="+shopid,
				data:{
					catid:that.catid,
					per_page:that.per_page,
					type:that.type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					that.pageData=res.data;
					that.pageLoad=true;
					that.catList=res.data.catList;
				}
			})
		},
		updateCartStat:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_product&ajax=1&shopid="+shopid,
				data:{
					catid:that.catid
				},
				dataType:"json",
				success:function(res){
					that.pageData.cart_total_num=res.data.cart_total_num;
					that.pageData.cart_total_money=res.data.cart_total_money;
					 
				}
			})
		},
		getRaty:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_shop&a=raty&ajax=1&shopid="+shopid,
				dataType:"json",
				success:function(res){
					that.ratyPage=res.data;
					that.pageLoad=true; 
				}
			})
		},
		getDetail:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_shop&a=detail&ajax=1&shopid="+shopid,
				dataType:"json",
				success:function(res){
					that.detailPage=res.data;
					that.pageLoad=true; 
				}
			})
		},
		setCat:function(catid){
			this.catid=catid;
			this.getProduct();
		},
		setPage:function(page){
			this.page=page;
			this.pageLoad=false;
			console.log(page)
			switch(page){
				case "raty":
					this.getRaty();
					break;
				case "detail":
					this.getDetail();
					break;
				default:
					this.getProduct();
					break;
			}
		},
		
		
	}
})

/***jquery***/
$(document).on("click",".shop-fav-toggle",function(){
	var tablename=$(this).attr("tablename");
	var objectid=$(this).attr("objectid");
	var that=$(this);
	$.get("/index.php?m=fav&a=toggle&ajax=1",{
		tablename:tablename,
		objectid:objectid
	},function(res){
		skyToast(res.message);
		if(res.error==1000){
			window.location="/index.php?m=login";
			return false;
		}else{
			if(res.data=='add'){
				that.addClass("btn-fav-active");
			}else{
				that.removeClass("btn-fav-active");
			}
		}
		
	},"json")
})

$(document).on("click","#header-more-btn",function(){
	$("#header-more-box").show();
})

$(document).on("click",".js-coupon-btn",function(){
	$("#modal-coupon").show();
})