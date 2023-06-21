var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			type:"online",
			sumMoney:0,
			catid:0,
			cdate:""
		}
	},
	created:function(){
		this.getLog();
	},
	methods:{
		setType:function(t){
			this.type=t;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		choiceForm:function(){
			this.per_page=0;
			this.isFirst=true;
			this.cdate=$("#cdate").val()
			this.getList();
			
		},
		getLog:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=bill_log&a=admin&ajax=1&shopid="+shopid,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.sumMoney=res.data.sumMoney;
					laydate.render({
						elem:"#cdate",
						type:"date"
					})
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=bill_log&a=admin&ajax=1&shopid="+shopid,
				dataType:"json",
				data:{
					per_page:that.per_page,
					type:that.type,
					catid:that.catid,
					cdate:that.cdate
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i  in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.sumMoney=res.data.sumMoney;
					that.per_page=res.data.per_page;
					that.isFirst=false;
				}
			})
		},
		del:function(item){
			var that=this;
			if(confirm("确认删除吗?")){
				$.ajax({
					url:"/module.php?m=bill_log&a=delete&ajax=1&shopid="+item.shopid+"&id="+item.id,
					dataType:"json",
					success:function(res){
						if(res.error){
							return false;
						}
						var list=[];
						for(var i in that.list){
							if(that.list[i].id!=item.id){
								list.push(that.list[i]);
							}
						}
						that.list=list;
					}
				})
			}
		}
	}
});