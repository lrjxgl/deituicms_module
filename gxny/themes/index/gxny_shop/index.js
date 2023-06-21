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
			newPro:{},
			shop:{},
			pageTab:"index",
			blogList:[]
		}
	},
	created:function(){
		this.shopid=shopid;
		this.getPage();
		this.getList();
		this.getBlog();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_shop&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid
				},
				success:function(res){
					that.shop=res.data.shop;
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
		getBlog:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_blog&a=list&ajax=1",
				dataType:"json",
				data:{
					shopid:this.shopid,
				},
				success:function(res){
					that.blogList=res.data.list;				
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