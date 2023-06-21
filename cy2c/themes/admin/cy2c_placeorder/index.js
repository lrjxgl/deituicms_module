var App=new Vue({
	el:"#App",
	data:function(){
		return {
			type:"new",
			isFirst:true,
			per_page:0,
			list:[],
			proList:[],
			showPro:false,
			placeid:0
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
				url:"/moduleadmin.php?m=cy2c_placeorder&ajax=1",
				dataType:"json",
				data:{
					type:this.type
				},
				success:function(res){
					that.list=res.data.list;
					that.per_page=res.data.per_page;
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
				url:"/moduleadmin.php?m=cy2c_placeorder&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					type:this.type
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
		getProduct:function(poid){
			var that=this;
			that.showPro=true;
			$.ajax({
				url:"/moduleadmin.php?m=cy2c_placeorder&a=product&ajax=1",
				dataType:"json",
				data:{
					poid:poid
				},
				success:function(res){
					that.proList=res.data.list;
				}
			})
		},
		finish:function(poid){
			var that=this;
			skyJs.confirm({
				title:"结账提示",
				content:"确认结账吗?",
				success:function(){
					$.ajax({
						url:"/moduleadmin.php?m=cy2c_placeorder&a=finish&ajax=1",
						dataType:"json",
						data:{
							poid:poid
						},
						success:function(res){
							skyToast(res.message) ;
							window.location.reload();
						}
					})
				}
			})
		}
	}
})