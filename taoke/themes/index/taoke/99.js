var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true
			 
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke&a=99&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.data;
					that.per_page=res.data.per_page;
					that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=taoke&a=99&ajax=1",
				data:{
					per_page:this.per_page
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.isFirst=false;
						that.list=res.data.data;
					}else{
						for(var i in res.data.data){
							that.list.push(res.data.data[i]);
						}
					}		
					that.per_page=res.data.per_page;
					
				}
			})
		},
		goDetail:function(id){
			window.location="/module.php?m=taoke&a=show&id="+id;
		}
	}
})