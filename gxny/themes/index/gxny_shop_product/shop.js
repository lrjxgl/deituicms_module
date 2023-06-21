var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			type:"free",
			isFirst:true,
			per_page:0,
			catid:0,
			catList:[],
			shopid:0,
			newPro:{}
			
		}
	},
	created:function(){
		this.shopid=shopid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_shop_product&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					shopid:this.shopid
				},
				success:function(res){
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.catList=res.data.catList;
					that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_shop_product&a=list&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					per_page:this.per_page,
					shopid:this.shopid,
					catid:this.catid
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					
				}
			})
		},
		setType:function(t){
			this.type=t;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		setCat:function(t){
			if(this.catid==t){
				this.catid=0;
			}else{
				this.catid=t;
			}
			
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		choiceProduct:function(pro){
			if(pro.isused==0){
				this.newPro=pro;
				console.log(this.newPro)
			}else{
				this.newPro={};
				window.location="/module.php?m=gxny_shop_product&a=show&id="+pro.id
			}
		},
		buy:function(pro){
			var that=this;
			skyJs.confirm({
				title:"购买提示",
				content:"确认购买菜园吗",
				success:function(){
					$.ajax({
						url:"/module.php?m=gxny_shop_product&a=buy&ajax=1",
						dataType:"json",
						data:{
							id:pro.id
						},
						success:function(res){
							skyJs.toast(res.message);
							if(res.error){
								if(res.error==22){
									window.location="/index.php?m=recharge"
								}else if(res.error==1000){
									window.location="/index.php?m=login"
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