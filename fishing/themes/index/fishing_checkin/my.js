var app=new Vue({
	el:"#App",
	data:function(){
		return {
			 
			ckList:[],
			 
		}
	},
	created:function(){
		 
		this.getcheckin();
		 
	},
	methods:{
		 
		getcheckin:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_checkin&a=my&ajax=1",
				dataType:"JSON",
		 
				success:function(res){
					if(res.error){
						skyJs.toast(res.message);
						return false;
					}
					that.ckList=res.data.list;
				}
			})
		},
		gocheckin:function(id){
			window.location="/module.php?m=fishing_checkin&a=show&id="+id;
		},
		goPlace:function(placeid){
			window.location="/module.php?m=fishing_place&a=show&placeid="+placeid;
		},
		del:function(item){
			var that=this;
			skyJs.confirm({
				content:"确认删除吗?",
				success:function(){
					$.ajax({
						url:"/module.php?m=fishing_checkin&a=delete&ajax=1",
						dataType:"JSON",
						data:{
							id:item.id
						},	 
						success:function(res){
							skyJs.toast(res.message);
							if(res.error){
								
								return false;
							}
							var list=[];
							for(var i in that.ckList){
								if(that.ckList[i].id!=item.id){
									list.push(that.ckList[i])
								}
							}
							that.ckList=list;
						}
					})
				}
			})
			
		} 
	}
})