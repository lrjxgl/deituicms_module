
var app = new Vue({
	el: '#app',
	data: {
		pageData: {},
		pageLoad:false,
		per_page:0,
		isFirst:true,
		list:[],
		show:"flex",
		catActive:"cl-money",
		ksShow:false,
		ksproduct:[],
		ksList:[],
		ksList2:[],
		ksid:0,
		ksid1:0,
		ksid2:0,
		catList:[],
		pcatid:0,
		catid:0,
		iPlan:1,
		iPrice:0,
		iPriceActive:"",
		iPriceClass:'icon-goup',
		iDiscount:0,
		iDiscountActive:"",
		iDiscountClass:'icon-godown',
		iChoice:0,
		totalMoney:0
		
	},
	created: function() {
		this.pcatid=pcatid;
		this.catid=catid;	 
		this.getPage();
		this.fixCart();
	},
	methods: {
		goProduct:function(id){
			window.location="/module.php?m=csc_product&a=show&id="+id;
		},
		getPage: function() {
			var that = this;
			$.ajax({
				url:"/module.php?m=csc_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					iPlan:this.iPlan,
				},
				dataType:"json",
				success:function(res){
					that.pageData = res.data;
					that.catList=res.data.catList;
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					that.list=res.data.list;
				}
			})
	 
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					iPlan:this.iPlan,
					iPrice:this.iPrice
				},
				dataType:"json",
				success:function(res){
					that.per_page=res.data.per_page;
					that.pageData = res.data;
					that.list=res.data.list;
					that.fixCart();
				}
			})
		},
		setCat:function(cid){
			var that=this;
			this.catid=cid;
			this.per_page=0;
			this.isFirst=0; 
			this.getList();
		},
		setPlan:function(i){
			if(this.iPlan==1){
				this.iPlan=0;
			}else{
				this.iPlan=1;
			}
			this.per_page=0;
			this.isFirst=0;
			this.getList();
		},
		setPrice:function(i){
			if(this.iPrice==0){
				this.iPrice=1;
				this.iPriceActive='iActive';
				this.iPriceClass="iActive icon-goup";
			}else if(this.iPrice==1){
				this.iPrice=2;
				this.iPriceActive='iActive';
				this.iPriceClass="iActive icon-godown";
			}else{
				this.iPriceActive='';
				this.iPriceClass=' icon-goup';
				this.iPrice=0;
			}
			this.per_page=0;
			this.isFirst=0;
			this.getList();
		},
		setDiscount:function(i){
			if(this.iDiscount==0){
				this.iDiscount=2;
				this.iDiscountActive='iActive';
				this.iDiscountClass="iActive icon-down";
			}else if(this.iDiscount==2){
				this.iDiscount=1;
				this.iDiscountActive='iActive';
				this.iDiscountClass="iActive icon-goup";
			}else{
				this.iDiscountActive='';
				this.iDiscountClass=' icon-down';
				this.iDiscount=0;
			}
			this.per_page=0;
			this.isFirst=0;
			this.getList();
		},
		setChoice:function(i){
			
		},
		addCart: function(id, ksid) {
			var that = this;
			var id = id;
			var ksid = ksid == undefined ? 0 : ksid;
			var amount = 1;
			$.ajax({
				url: '/module.php?m=csc_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",
				success: function(res) {
					if(res.error==1000){
						window.location="/index.php?m=login"
						return false;
					}
					if(res.error){
						skyToast(res.message);
						return false;
					}
					var list = that.pageData.list;
					for (var i = 0; i < list.length; i++) {
						if (list[i].id == id) {
							if (res.data.amount > 0) {
								list[i].incart = 1;
							}
							list[i].cart_amount = res.data.amount;
							break;
						}
					}
					that.pageData.list = that.parseList(list);
					that.fixCart();
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
				url: '/module.php?m=csc_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid
				},
				method: 'GET',
				dataType: "json",

				success: function(res) {
					if(res.error){
						skyToast(res.message);
						return false;
					}
					var list = that.pageData.list;
					for (var i = 0; i < list.length; i++) {
						if (list[i].id == id) {

							list[i].cart_amount = res.data.amount;
							break;
						}
					}

					that.pageData.list = that.parseList(list);
					that.fixCart();
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
				url: '/module.php?m=csc_cart&a=add&ajax=1',
				data: {
					productid: id,
					amount: amount,
					ksid: ksid,
					isdelete: isdelete
				},
				method: 'GET',
				dataType: "json",

				success: function(res) {
					if(res.error){
						skyToast(res.message);
						return false;
					}
					var list = that.pageData.list;
					for (var i = 0; i < list.length; i++) {
						if (list[i].id == id) {
							if (res.data.amount == 0) {
								list[i].incart = 0;
							}
							list[i].cart_amount = res.data.amount;
							break;
						}
					}

					that.pageData.list = that.parseList(list);
					that.fixCart();
				}
			})
		},
		parseList: function(list) {
			for (var i = 0; i < list.length; i++) {
				if (list[i].cart_num > 0) {
					list[i].cart_num_class = "prolist-item-row-cart-active";
				} else {
					list[i].cart_num_class = "";
				}
			}
			return list;
		},
		fixCart:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_cart&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error==0){
						that.totalMoney=res.data.total_money
					}
					
				}
			})
		}
		
	}
})
