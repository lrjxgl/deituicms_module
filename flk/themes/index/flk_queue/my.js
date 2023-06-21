var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:"",
			pageLoad:false,
			per_page:0,
			isFirst:true,
			type:"all",
			ewmClass:"",
			ewm:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=flk_queue&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					} 
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=flk_queue&a=my&ajax=1",
				dataType:"json",
				data:{
					type:that.type,
					per_page:that.per_page
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					} 
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					that.per_page=res.data.per_page;
				}
			})
		},
		setType:function(type){
			this.type=type;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		showEwm:function(t,p){
			var that=this;
			
			$.ajax({
				url:"/module.php?m=flk_queue&a=ewm&type="+t+"&orderid="+p,
				
				dataType:"json",
				success:function(res){
					that.ewm=res.ewm;
					that.ewmClass="flex";
				}
			})
		},
		cancel:function(item){
			var that=this;
			
			$.ajax({
				url:"/module.php?m=flk_queue&a=cancel&ajax=1&id="+item.id,
				
				dataType:"json",
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					}
					item.status=4;
					item.isback=1;
					item.isfinish=1;
					
				}
			})
		},
		daxin:function(item){
			var that=this;
			
			$.ajax({
				url:"/module.php?m=flk_queue&a=daxin&ajax=1&id="+item.id,
				
				dataType:"json",
				success:function(res){
					skyToast(res.message)
					if(res.error){
						return false;
					}
					item.status=2;
					item.isback=1;
					
				}
			})
		}
	}
})