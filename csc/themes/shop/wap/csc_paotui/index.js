var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			addMoneyClass:"",
			aid:0,
			amoney:1,
			type:"all"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(t){
			this.type=t;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		showAddMoney:function(aid){
			this.addMoneyClass='flex-col';
			this.aid=aid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_paotui&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					that.list=res.data.list;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_paotui&ajax=1",
				data:{
					type:this.type,
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list[i].push(res.data.list[i]);
						}
					}
					
				}
			})
		},
		cancel:function(id){
			var that=this;
			if(confirm("确认取消吗?")){
				$.ajax({
					url:"/moduleshop.php?m=csc_paotui&a=cancel&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						skyToast(res.message);
						var list=new Array();
						for(var i in that.list){
							if(that.list[i].id!=id){
								list.push(that.list[i]);
							}
						}
						that.list=list;
					}
				})
			}
			
		}, 
		finish:function(id){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_paotui&a=finish&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					 if(!res.error){
						 window.location.reload();
					 }
				}
			})
			
		}
	}
})