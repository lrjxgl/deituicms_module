var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			acList:[],
			type:"new",
			task_action:""
		}
	},
	created:function(){
		 
		this.getPage();
	},
	watch:{
		task_action:function(n,o){
			this.task_action=n;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		}
	},
	methods:{
		setType:function(t){
			this.type=t;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gxny_animal_order_task&ajax=1",
				data:{
					type:this.type,
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.acList=res.data.acList;
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
				url:"/moduleshop.php?m=gxny_animal_order_task&ajax=1",
				data:{
					type:this.type,
					per_page:that.per_page,
					task_action:this.task_action
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
		finish:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gxny_animal_order_task&a=finish&ajax=1",
				dataType:"json",
				data:{
					taskid:item.taskid
				},
				success:function(res){
					item.status=2;
					item.status_name="待验收";
				}
			})
		}
	}
})
