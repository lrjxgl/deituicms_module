msApp=new Vue({
	el:"#App",
	data:function(){
		return {
			proList:[],
			list:[],
			title:"",
			zblive:"",
			proShow:false
		}
	},
	created:function(){
		this.getPage()
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&ajax=1",
				data:{
					room_id:room_id
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.zblive=res.data.zblive;
				}
			})
		},
		getList:function(){
			
		},
		search:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m="+that.zblive.tablename+"&a=list&ajax=1",
				data:{
					keyword:that.title
				},
				dataType:"json",
				success:function(res){
					
					that.proList=res.data.list;
					that.proShow=true;
				}
			})
		},
		add:function(productid){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&a=save&ajax=1",
				data:{
					room_id:room_id,
					productid:productid
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		},
		change:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&a=change&ajax=1",
				data:{
					room_id:room_id,
					id:item.id,
					orderindex:item.orderindex
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		},
		del:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&a=delete&ajax=1",
				data:{
					room_id:room_id,
					id:item.id 
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		},
		up:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&a=up&ajax=1",
				data:{
					room_id:room_id,
					id:item.id 
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					item.status=1;
					var msg = JSON.stringify({
						type: "sendProduct",
						gid: ws_gid,
						 
						product: item
					});
					ws.send(msg);
				}
			})
		},
		down:function(item){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=zblive_product&a=down&ajax=1",
				data:{
					room_id:room_id,
					id:item.id 
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.getPage();
				}
			})
		}
	}
})