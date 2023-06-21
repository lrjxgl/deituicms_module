var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			certList:[],
			ratyList:[],
			shop:{},
			isFollow:0,
			per_page:0,
			isFirst:false,
			tab:"all"
		}
	},
	created:function(){
		this.isFollow=isFollow;
		this.getPage();
		this.getRatyList();
	},
	methods:{
		goCert:function(item){
			var imgs=item.imgslist;
			js_thumb_index=0;
			if(js_thumb_swiper!=false){
				js_thumb_swiper.destroy();
				js_thumb_swiper=false;
			}
			$("#js-thumb-box .swiper-wrapper").html("");
			for(var i=0;i<imgs.length;i++){
	
				$("#js-thumb-box .swiper-wrapper").append('<div class="swiper-slide"><img src="'+imgs[i]+'" /></div>');
			}
			$("#js-thumb-box").show();
			js_thumb_swiper = new Swiper ('#js-thumb-swiper-container', {
			  loop: false,
			  pagination: {
			    el: '#js-thumb-swiper-pagination'
			  }
			})
			if(js_thumb_index!=0){
				js_thumb_swiper.slideTo(js_thumb_index,0); 	
			}
		},
		setTab:function(t){
			this.tab=t;
		} ,
		goDetail:function(lessonid){
			window.location="/module.php?m=tutor_lesson&a=show&lessonid="+lessonid;
		},
		goOrder:function(id){
			window.location="/module.php?m=tutor_order&a=confirm&lessonid="+id;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=tutor_shop&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.per_page=res.data.per_page;
					that.isFirst=false;
					that.list=res.data.list;
					that.shop=res.data.shop;
					if(res.data.certList){
						that.certList=res.data.certList;
					}
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=tutor_shop&ajax=1",
				data:{
					shopid:shopid,
					per_page:that.per_page
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
				}
			})
		},
		toggleFollow:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=tutor_shop&a=togglefollow&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.isFollow=res.data.isFollow;
				}
			})
		},
		getRatyList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=tutor_shop&a=raty&ajax=1",
				data:{
					shopid:shopid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.ratyList=res.data.list;
				}
			})
		}
  
	}
})