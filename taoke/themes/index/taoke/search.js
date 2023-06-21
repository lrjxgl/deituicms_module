var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			word:"",
			orderby:""
		}
	},
	created:function(){
		this.word=keyword;
		this.getPage();
	},
	methods:{
		setOrder:function(order){
			this.orderby=order;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke&a=search&ajax=1&word="+this.word,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
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
				url:"/module.php?m=taoke&a=search&ajax=1&word="+this.word,
				data:{
					per_page:this.per_page,
					orderby:this.orderby
				},
				dataType:"json",
				success:function(res){
					if(that.isFirst){
						that.isFirst=false;
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
		goDetail:function(id){
			window.location="/module.php?m=taoke&a=show&id="+id;
		}
	}
})