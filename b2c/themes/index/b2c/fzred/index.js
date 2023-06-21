var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			catid:0,
			catList:[],
			msList:[],
			ptList:[],
			 
			msTime:0,
			h:10,
			m:10,
			s:10
		}
	},
	created:function(){
		this.getPage();
		this.getCategory();
		this.getMsList();
		this.getPtList();
	},
	methods:{
		setCat:function(catid){
			this.catid=catid;
			if(catid==0){
				this.getPage();
			}else{
				this.getList();
			}
			
		},
		
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2c&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.recList;
				}
				
			})
		},
		getCategory:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2c_category&ajax=1",
				dataType:"json",
				success:function(res){
					that.catList=res.data.catList;
				}
				
			})
		},
		goProduct:function(id){
			window.location="/module.php?m=b2c_product&a=show&id="+id
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2c_product&a=list&ajax=1",
				dataType:"json",
				data:{
					catid:this.catid
					
				},
				success:function(res){
					that.list=res.data.list;
				}
				
			})
		},
		getMsList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2c_flash&ajax=1",
				dataType:"json",
				data:{
					limit:6,
					type:"doing"
				},
				success:function(res){
					that.msList=res.data.list;
					that.msTime=parseInt(res.data.time);
					console.log(res.data.time)
					setInterval(function(){
						if(that.msTime<0){
							return false;
						}
						var t=that.msTime;
						that.msTime--;
						var h=0;
						var m=0;
						var s=0;
						if(t>3600){					
							h=parseInt(t/3600);
						}
						if(t>60){
							m=parseInt((t-h*3600)/60);
						}
						s=t-h*3600-m*60;
						//加0
						h=h<10?'0'+h:h;
						m=m<10?'0'+m:m;
						s=s<10?'0'+s:s; 
						that.h=h;
						that.m=m;
						that.s=s;
					},1000);
				}
				
			})
		},
		 
		getPtList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2c_pintuan&ajax=1",
				dataType:"json",
				data:{
					limit:6,
					type:"doing"
				},
				success:function(res){
					that.ptList=res.data.list;
					
					 
				}
				
			})
		}
	}
})