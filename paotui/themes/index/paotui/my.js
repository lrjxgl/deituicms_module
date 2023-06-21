var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:{},
			isFirst:true,
			per_page:0,
			addMoneyClass:"",
			aid:0,
			amoney:1,
			type:"all",
			raty_grade:9,
			raty_content:"",
			showRaty:false,
			raty_paotui:0
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
		goRaty:function(item){
			this.raty_paotui=item;
			this.showRaty=true;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=my&ajax=1",
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
				url:"/module.php?m=paotui&a=my&ajax=1",
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
			$.ajax({
				url:"/module.php?m=paotui&a=cancel&ajax=1&id="+id,
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
		},
		pay:function(id){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=pay&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					window.location=res.data.payurl;
				}
			})
			
		},
		addMoney:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=addMoney&ajax=1&id="+this.aid+"&money="+this.amoney,
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					if(!res.error){
						 window.location.reload();
					}
				}
			})
		},
		finish:function(id){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=finish&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					 if(!res.error){
						 window.location.reload();
					 }
				}
			})
			
		},
		ratySave:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=ratysave&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					id:that.raty_paotui.id,
					grade:that.raty_grade,
					content:that.raty_content
				},
				success:function(res){
					skyToast(res.message);
					if(!res.error){
						that.raty_paotui.israty=1;
						that.showRaty=false;
					}
				}
			})
		},
	}
})