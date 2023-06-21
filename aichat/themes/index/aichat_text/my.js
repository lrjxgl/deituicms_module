var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			picModal:{}, 
			article:{},
			showArticle:false,
			rscount:0,
			 
		}
	},
	created:function(){
		var that=this;
		if(window.innerWidth>500){
			this.picModal={width:'50rem',marginTop:'-30rem',left:'50%',marginLeft:'-25rem'}
		}
		this.getPage();
		this.getList();
		 
		
	},
	methods:{
		 
		goLast:function(){
			for(var i=0;i<this.list.length;i++){
				if(this.list[i].id==this.article.id){
					if(i!=0){
						this.article=this.list[i-1];
						break;
					}
				}
			}
		},
		goNext:function(){
			console.log("next",this.article,this.article.id)
			for(var i=0;i<this.list.length;i++){
				if(this.list[i].id== this.article.id){
					if(i<this.list.length-1){
						this.article=this.list[i+1];
						console.log(i+1)
						break;
					}
				}
			}
			console.log(this.article)
		},
		 
		showItem:function(item){
			this.article=item;
			this.showArticle=true;
		},
		delItem:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_text&a=delete&ajax=1",
				data:{
					id:item.id
				},
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					that.showArticle=false;
					var list=[];
					for(var i in that.list){
						if(item.id!=that.list[i].id){
							list.push(that.list[i])
						}
					}
					that.list=list;
				}
			})
		},
		 
		 
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_text&a=create&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.text_token=res.data.text_token;
					
				}
			})
		},
		refresh:function(){
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=aichat_text&a=my&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
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
					
					that.rscount=res.data.rscount;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		}
	}
})