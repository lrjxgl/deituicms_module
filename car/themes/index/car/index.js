var App=new Vue({
	el:"#app",
	data:function(){
		return {
			list:[],
			pageLoad:false,
			type:"recommend",
			per_page:0,
			isFirst:true,
			 
			
		}
	},
	created:function(){
		if(!this.getCache()){
			this.getPage();
		}
		
	},
	methods:{
		getCache:function(){
			var v=localStorage.getItem("carIndex");
			if(v){
				var res=JSON.parse(v);
				this.list=res.list;
				this.pageLoad=res.pageLoad;
				this.type=res.type;
				this.per_page=res.per_page;
				this.isFirst=res.isFirst;
				var time=Date.parse(new Date())/1000;
				if(res.expire<time){
					return false;
				}
				return true;
			}else{
				return false;
			}
			
		},
		setCache:function(){
			var v=this.$data;
			v.expire= Date.parse(new Date())/1000+300; 
			localStorage.setItem("carIndex",JSON.stringify(v));
		},
		setType:function(type){
			this.type=type;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		goBlog:function(id){
			window.location="/module.php?m=car_product&a=show&productid="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=car_product&a=list&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.list=res.data.list;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.setCache();
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=car_product&a=list&ajax=1",
				data:{
					type:that.type,
					lat:lat,
					lng:lng
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					 
					if(that.isFirst){
						that.list=res.data.list;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.setCache();
				}
			})
		},
	}
})