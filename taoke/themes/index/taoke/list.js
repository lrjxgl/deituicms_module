var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			tagname:"",
			tagList:[],
			orderby:""
		}
	},
	created:function(){
		this.catid=catid; 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke&a=list&ajax=1&catid="+this.catid,
				dataType:"json",
				success:function(res){
					that.list=res.data.data;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.tagList=res.data.taoke_tags;
				}
			})
		},
		setTag:function(tag){
			if(this.tagname==tag){
				this.tagname="";
			}else{
				this.tagname=tag;
			}
			
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		setOrder:function(order){
			this.orderby=order;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getList:function(){
			var that=this;
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=taoke&a=list&ajax=1&catid="+this.catid,
				data:{
					per_page:this.per_page,
					tagname:this.tagname,
					orderby:this.orderby
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