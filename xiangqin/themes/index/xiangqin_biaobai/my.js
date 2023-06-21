var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			type:"receive"
		}
	},
	created:function(){
		var s=localStorage.getItem("xiangqin-notice-biaobai"); 
		if(s!=null){
			this.type=s;
		} 
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			localStorage.setItem("xiangqin-notice-biaobai",type); 
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=xiangqin_biaobai&a=my&ajax=1",
				dataType:"json",
				data:{
					type:this.type
				},
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
				url:"/module.php?m=xiangqin_biaobai&a=my&ajax=1",
				data:{			 
					per_page:that.per_page,
					type:this.type
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
		pass:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=xiangqin_biaobai&a=pass&ajax=1",
				dataType:"json",
				data:{
					id:item.id
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					item.status_name="已通过";
					item.status=1;
				}
			})
		},
		forbid:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=xiangqin_biaobai&a=pass&ajax=1",
				dataType:"json",
				data:{
					id:item.id
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					item.status_name="已拒绝"
					item.status=2;
				}
			})
		},
		goPm:function(u){
			var userid=0;
			if(this.type=='receive'){
				userid=u.userid;
			}else{
				userid=u.touserid;
			}
			window.location="/index.php?m=pm&a=detail&userid="+userid;
		},
		goPeople:function(u){
			var userid=0;
			if(this.type=='receive'){
				userid=u.userid;
			}else{
				userid=u.touserid;
			}
			window.location="/module.php?m=xiangqin_people&a=show&userid="+userid;
		}
	},
	 
})