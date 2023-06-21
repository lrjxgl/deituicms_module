var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			isFirst:true,
			per_page:0,
			modalShow:false,
			taskContent:"",
			orderid:"",
			type:"all"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(type){
			this.type=type;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		showModal:function(item){
			this.orderid=item.orderid;
			this.modalShow=true;
		},
		taskSubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_animal_order_task&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					orderid:this.orderid,
					content:this.taskContent,
					tablename:"animal_order"
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.modalShow=false;
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gxny_animal_order&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message)
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
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=gxny_animal_order&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					per_page:this.per_page
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					if(that.isFirst){
						that.isFirst=false;
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
				}
			})
		},
		cancel:function(item){
			var that=this;
			that.orderid=item.orderid;
			skyJs.confirm({
				content:"确认不养了吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=gxny_animal_order&a=out&ajax=1",
						dataType:"json",
						data:{
							orderid:that.orderid
						},
						success:function(res){
							if(res.error){
								skyToast(res.message)
								return false;
							}
							that.getPage(); 
						}
					})
				}
			})
		}
	}
})