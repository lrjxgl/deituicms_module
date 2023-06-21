var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			type:"all"
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		goLession:function(lessonid){
			window.location="/module.php?m=tutor_lesson&a=show&lessonid="+lessonid
		},
		goShop:function(shopid){
			window.location="/module.php?m=tutor_shop&shopid="+shopid
		},
		setType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=tutor_order&ajax=1",
				data:{
					type:this.type
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
				url:"/moduleshop.php?m=tutor_order&ajax=1",
				data:{
					type:this.type,
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
		
		cancel:function(item){
			var that=this;
			skyJs.confirm({
				title:"确认提示",
				content:"确认取消回收吗？",
				success:function(){
					$.ajax({
						url:"/moduleshop.php?m=tutor_order&a=cancel&ajax=1",
						data:{
							orderid:item.orderid
						},
						dataType:"json",
						success:function(res){
							
							skyJs.toast(res.message);
							if(!res.error){
								item.status=4;
								item.status_name="已取消"
							}
						}
					})
				}
			})
		}
	}
})
