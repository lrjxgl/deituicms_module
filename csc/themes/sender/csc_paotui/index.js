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
		var that=this;
		this.getPage();
		setInterval(function(){
			that.getPage();
		},10000)
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
				url:"/sender.php?m=csc_paotui&ajax=1",
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
				url:"/sender.php?m=csc_paotui&ajax=1",
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
		uconfirm:function(id){
			var that=this;
			if(confirm("确认接单吗?")){
				$.ajax({
					url:"/sender.php?m=csc_paotui&a=confirm&ajax=1&id="+id,
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
			
		}
		 
	}
})