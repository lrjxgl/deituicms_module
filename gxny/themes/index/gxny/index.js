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
			blogList:[],
			shop:{}
		}
	},
	created:function(){
		this.shopid=shopid;
		this.getPage();
		this.getBlogList();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_shop_product&a=my&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					shopid:this.shopid
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.catList=res.data.catList;
					that.isFirst=false;
					
					that.pageLoad=true;
				}
			})
		},
		 
		getBlogList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_blog&a=follow&ajax=1",
				dataType:"json",
				data:{
					 
					per_page:this.per_page,
					shopid:this.shopid 
				},
				success:function(res){
					if(that.isFirst){
						that.blogList=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.blogList.push(res.data.list[i])
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
			window.location="/module.php?m=gxny_shop_product&a=show&id="+pro.id
		},
		goBlog:function(id){
			window.location="/module.php?m=gxny_blog&a=show&id="+id;
		}
	}
})