var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			addrList:[],
			user_address_id:0,
			shop:0,
			lmid:0,
			totalMoney:0,
			allMoney:0,
			shopList:[],
			proList:[],
			Tab:"",
			cartList:[]
		}
	},
	created:function(){
		this.getPage();
		this.getCart();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_paotui&ajax=1",
				dataType:"json",
				success:function(res){
					that.user_address_id=res.data.user_address_id;
					that.addrList=res.data.addrList;
					that.shop=res.data.shop;
					that.getLmShop();
				}
			})
		},
		getLmShop:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_paotui_lmshop&ajax=1&lmid="+this.lmid,
				dataType:"json",
				success:function(res){
					that.proList=res.data.proList; 
					that.shopList=res.data.list;
					that.lmid=res.data.lmid;
					that.totalMoney=res.data.totalMoney;
					that.allMoney=parseFloat(that.shop.paotui_money)+parseFloat(res.data.totalMoney);
				}
			})
		},
		setShop:function(item){
			this.lmid=item.lmid;
			this.getLmShop();
		},
		cartMinus:function(item){
			if(item.cartnum>0){
				item.cartnum--;
				this.cartPost(item)
			}
			
		},
		cartPlus:function(item){
			if(item.cartnum<10){
				item.cartnum++;
				this.cartPost(item)
			}
			
		},
		cartPost:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_paotui_lmshop_cart&a=add&ajax=1",
				data:{
					productid:item.productid,
					num:item.cartnum
				},
				dataType:"json",
				success:function(res){
					that.getLmShop();
				}
			})
		},
		getCart:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_paotui_lmshop_cart&ajax=1",
			 
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.cartList=res.data.list; 
				}
			})
		},
		changeAddr:function(){
			
		},
		formSubmit:function(){
			if(confirm("确认下单吗？")){
				$.ajax({
					url:"/module.php?m=csc_paotui&a=save&ajax=1",
					dataType:"json",
					method:"POST",
					data:$("#form").serialize(),
					success:function(res){
						skyToast(res.message);
						if(res.error) return false;
						if(res.data.action=="success"){
							window.location="/module.php?m=csc_paotui&a=success";
						}else{
							window.location=res.data.payurl;
						}
					}
				})
			}
			
		}
	}
})