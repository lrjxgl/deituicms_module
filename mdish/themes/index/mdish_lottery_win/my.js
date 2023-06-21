var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			type:"all",
			isFirst:true,
			per_page:0
		}
	},
	created:function(){ 
		this.getPage();
	 
	},
	methods:{
		setType:function(t){
			this.type=t;
			this.per_page=0;
			this.isFirst=true;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=mdish_lottery_win&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					 that.list=res.data.list;
					 that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=mdish_lottery_win&a=my&ajax=1",
				dataType:"json",
				data:{
					type:this.type,
					per_page:this.per_page
				},
				success:function(res){
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
				}
			})
		}
	}
})
var sTop=$("#sTop").offset().top;
$(window).on("scroll",function(e){
	var t=$(window).scrollTop();
	if(t+60>sTop){
		$("#tabs").addClass("tabFixed")
	}else{
		$("#tabs").removeClass("tabFixed")
	}
})