var app=new Vue({
	el:"#App",
	data:function(){
		return {
			data:[],
			productid:0,
			cjBox:false,
			price:0,
			isFav:0,
			isFollow:0,
			cmBox:false
		}
	},
	created:function(){
		this.productid=productid;
		var that=this;
		this.getPage();
		this.getFav();
	},
	methods:{
		search:function(e){
			window.location="/module.php?m=ershou_search&keyword="+e;
		},
		goHome:function(){
			window.location="/module.php?m=ershou_home&userid="+this.data.userid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_product&a=show&ajax=1&productid="+this.productid,
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
					 
				}
			})
		},
		getFav:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=fav&a=isfav&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_ershou_product",
					objectid:this.productid
				},
				success:function(res){
					that.isFav=res.data;
					 
				}
			})
		},
		getFollow:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=isFollow&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.data.userid
				},
				success:function(res){
					that.isFollow=res.data;
					 
				}
			})
		},
		followToggle:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=follow&a=toggle&ajax=1",
				dataType:"json",
				data:{
					t_userid:this.data.userid
				},
				success:function(res){
					if(!res.error){
						that.isFollow=res.follow; 
					}else{
						skyToast(res.message)
					}
					
					 
				}
			})
		},
		writePrice:function(p){
			var p=this.price+""+p;
			this.price=parseInt(p);
		},
		removePrice:function(){
			var p=this.price+''
			p=p.substr(0,p.length-1);
			if(p==''){
				this.price=0;
			}else{
				this.price=parseInt(p);
			}
			
		},
		baojia:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_baojia&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					productid:this.productid,
					money:this.price
				},
				success:function(res){
					skyToast(res.message);
					that.cjBox=false;
				}
			})
		},
		buy:function(){
			 
			window.location="/module.php?m=ershou_order&a=confirm&productid="+this.productid;
		},
		favToggle:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=fav&a=toggle&ajax=1",
				dataType:"json",
				data:{
					tablename:"mod_ershou_product",
					objectid:this.productid
				},
				success:function(res){
					skyToast(res.message)
					if(res.data=='delete'){
						that.isFav=0;
					} else{
						that.isFav=1;
					}
				}
			})
		}
		
	}
})