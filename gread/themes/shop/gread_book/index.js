var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			isFirst:true,
			per_page:0,
			showChangeBox:false,
			num:0,
			productid:0,
			keyword:"",
			catList:[],
			catid:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		search:function(){
			this.catid=0;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		setCat:function(catid){
			this.keyword="";
			this.catid=catid;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			 
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_book&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					keyword:this.keyword
					
				},
				success:function(res){
					that.catList=res.data.catList;
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					
				}
			})
		},
		getList:function(){
			if(this.per_page==0 && !this.isFirst){
				return false;
			}
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_book&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					keyword:this.keyword,
					catid:this.catid
				},
				success:function(res){
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i])
						}
					}
					that.per_page=res.data.per_page;
					
				}
			})
		},
		 
		add:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=gread_book&a=shopsave&ajax=1",
				dataType:"json",
		 
				data:{
					bookid:item.bookid 
				},
				success:function(res){
					skyJs.toast(res.message); 
					if(res.error){
						return false;
					}
					item.inshop=1;
				}
			})
		}
	}
	
})