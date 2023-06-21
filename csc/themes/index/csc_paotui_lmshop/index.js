var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			shopid:0,
			lmid:0,
			totalMoney:0,
			allMoney:0,
			shopList:[],
			proList:[],
		}
	},
	created:function(){
		 
		this.getLmShop();
	},
	methods:{
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
					that.allMoney=parseFloat(res.data.totalMoney);
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
	}
})
