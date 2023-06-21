var App=new Vue({
	el:"#App",
	data:function(){
		return {
			loupan:{},
			list:[],
			lpid:lpid,
			typeList:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=house_peitao&ajax=1",
				data:{
					lpid:lpid
				},
				dataType:"json",
				success:function(res){
					console.log(res)
					that.loupan=res.data.loupan;
					that.list=res.data.list;
					that.typeList=res.data.typeList;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=house_peitao&ajax=1",
				data:{
					lpid:lpid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;					 
				}
			})
		},
		save:function(id){
			console.log(id);
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=house_peitao&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:$("#form"+id).serialize(),
				success:function(res){
					that.getList();
				}
			})
		},
		del:function(id){
			if(confirm("确定删除？")){
				var that=this;
				$.ajax({
					url:"/moduleadmin.php?m=house_peitao&a=delete&ajax=1",
					data:{
						id:id
					},
					dataType:"json",
					success:function(res){
						that.getList();
					}
				})
			} 
			
		}
	}
})