 var sketch = VueColor.Sketch;
var App=new Vue({
	el:"#app",
	components:{
		'sketch-picker': sketch
	},
	data:function(){
		return {
			pageTab:"pagelist",
			iswap:false,
			colors:"#ff6600",
			colorCss:"",
			pages:{},
			h5video:{},
			aniList:[],
			styleList:[],
			itypeList:[],
			pageLoad:false,
			addPageCss:"",
			items:[],
			itemId:0,
			page:{},
			pageShow:false,
			pageid:0,
			viewPageUrl:"",
			styleEl:"",
			styleBox:"",
			itype:"text"
		}
	},
	created:function(){
		this.getPage();
		this.iswap=iswap=="1"?1:0;
		this.viewPageUrl="/module.php?m=h5video&a=show&vid="+vid
	},
	methods:{
		setTab:function(tab){
			this.pageTab=tab;
		},
		changeColor:function(e){
			
			if(this.itemId!=0){
				console.log(this.itemId);
				for(var i=0;i<this.items.length;i++){
					if(this.items[i].id==this.itemId){
						this.items[i].color=e.hex;
					}
				}
			}else{
				this.colors=e.hex;
			}
			
		},
		choiceColor:function(itemId){
			if(itemId!=0){
				this.itemId=itemId;
			}else{
				this.itemId=0;
			}
			this.colorCss="display:block;"
		},
		hideColorCss:function(){
			this.colorCss=""
		},
		viewPage:function(){
			this.pageTab="view";
			this.viewPageUrl="/module.php?m=h5video&a=show&vid="+vid+"&pageid="+this.pageid+"&t="+new Date()
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=h5video&a=data&ajax=1&vid="+vid,
				dataType:"json",
				success:function(res){
					that.h5video=res.data.data;
					that.pages=res.data.pages;
					that.aniList=res.data.aniList;
					that.styleList=res.data.styleList;
					that.itypeList=res.data.itypeList;
					that.pageLoad=true;
					that.pageShow=false;
				}
			})
		},
		addPageBox:function(){
			this.addPageCss="display:block;";
		},
		hideAddPage:function(){
			this.addPageCss="";
		},
		savePage:function(el){
			var that=this;
			this.addPageCss="";
			$.ajax({
				url:"/module.php?m=h5video_page&a=save&ajax=1",
				dataType:"json",
				data:$(el).serialize(),
				method:"POST",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.getPage();
				}
			})
		},
		delPage:function(pageid){
			var that=this;
			if(confirm("确认删除吗")){
				$.ajax({
					url:"/module.php?m=h5video_page&a=delete&ajax=1&pageid="+pageid,
					dataType:"json",
					success:function(res){
						that.getPage();
					}
				})
			}
			
		},
		setPage:function(pageid){
			var that=this;
			that.pageid=pageid;
			that.pageShow=true;
			that.pageTab="design";
			$.ajax({
				url:"/module.php?m=h5video_page&a=items&ajax=1&pageid="+pageid,
				dataType:"json",
				success:function(res){
					that.page=res.data.page;
					that.items=res.data.items;
									 
				}
			})
		},
		saveItem:function(obj){
			var that=this;
			$.ajax({
				url:"/module.php?m=h5video_page_item&a=save&ajax=1",
				dataType:"json",
				data:$(obj).serialize(),
				method:"POST",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
					that.setPage(that.pageid);
					skyToast("保存成功");
					$("#itemAddForm").find("[name='imgurl']").val("");
					$("#itemAddForm").find("[name='title']").val("");
					$("#itemAddForm").find("[name='content']").val("");
					$("#itemAddForm").find(".js-imgbox").html("");
				}
			})
		},
		delItem:function(id){
			if(confirm("确认删除吗")){
				var that=this;
				$.ajax({
					url:"/module.php?m=h5video_page_item&a=delete&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						that.pageData=res.data;
						that.pageLoad=true;
						that.setPage(that.pageid);
						skyToast("删除成功");
					}
				})
			}
		},
		showStyle:function(el,itemid){
			this.styleEl=el;
			if(itemid!=undefined){
				this.itemId=itemid;
			}
			this.styleBox="display:block;";
		},
		hideStyle:function(){
			this.styleBox="";
			this.itemId=0;
		},
		setStyle:function(styleid,title){
			console.log($(this.styleEl).find(".style-value").length);
			if(this.itemId){
				for(var i=0;i<this.items.length;i++){
					if(this.items[i].id==this.itemId){
						this.items[i].styleid=styleid;
						this.items[i].style_title=title;
					}
					
				}
			}else{
				$(this.styleEl).find(".style-value").val(styleid);
				$(this.styleEl).find(".style-label").html(title);
			}
			
			this.styleBox="";
			this.itemId=0;
		}
	}
})