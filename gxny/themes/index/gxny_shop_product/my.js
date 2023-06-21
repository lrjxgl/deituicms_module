var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			cdList:[],
			cdModalShow:false,
			caidans:[],
			doPro:{},
			whModalShow:false,
			whList:["施肥","除草","杀虫","修剪"],
			weihu:"",
			czModalShow:false,
			czContent:"",
			user_address_id:0,
			addrList:[]
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_shop_product&a=my&ajax=1",
				data:{
					catid:this.catid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_shop_product&a=my&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		showCaidan:function(item){
			var that=this;
			that.doPro=item;
			$.ajax({
				url:"/module.php?m=gxny_shop_category&a=cdlist&ajax=1",
				data:{
					catid:item.catid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.cdList=res.data.cdList;
					that.cdModalShow=true;
				}
			})
		},
		zhongcai:function(){
			console.log(this.caidans)
			var that=this;
			
			$.ajax({
				url:"/module.php?m=gxny_order_task&a=zhongcai&ajax=1",
				data:{
					id:this.doPro.id,
					caidans:this.caidans
				},
				dataType:"json",
				type:"POST",
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					that.getPage(); 
					that.doPro={};
					that.cdModalShow=false;
				}
			})
		},
		showTask:function(item){
			var that=this;
			that.doPro=item;
			that.whModalShow=true;
		},
		taskPost:function(){
			 
			var that=this;
			
			$.ajax({
				url:"/module.php?m=gxny_order_task&a=weihu&ajax=1",
				data:{
					id:this.doPro.id,
					weihu:this.weihu
				},
				dataType:"json",
				type:"POST",
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					that.getPage(); 
					that.doPro={};
					that.whModalShow=false;
				}
			})
		},
		showCaizhai:function(item){
			var that=this;
			that.doPro=item;
			$.ajax({
				url:"/index.php?m=user_address&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.addrList=res.data.list;
					that.czModalShow=true;
				}
			})
			
		},
		czPost:function(){
			 
			var that=this;
			
			$.ajax({
				url:"/module.php?m=gxny_order_task&a=caizhai&ajax=1",
				data:{
					id:this.doPro.id,
					content:this.czContent,
					user_address_id:this.user_address_id
				},
				dataType:"json",
				type:"POST",
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					that.getPage(); 
					that.doPro={};
					that.czModalShow=false;
				}
			})
		},
		buy:function(productid){
			var that=this;
			skyJs.confirm({
				title:"购买提示",
				content:"确认续费菜园吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=gxny_shop_product&a=buy&ajax=1",
						dataType:"json",
						data:{
							id:productid,
							owner:1
						},
						success:function(res){
							skyJs.toast(res.message);
							if(res.error){
								if(res.error==22){
									window.location="/index.php?m=recharge"
								}
								return false;
							}
							that.isFirst=true;
							that.per_page=0;
							that.getList();
						}
					})
				}
			})
			
		}
	}
})
