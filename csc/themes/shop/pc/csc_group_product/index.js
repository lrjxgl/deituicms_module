var app=new Vue({
	el:"#App",
	data:function(){
		return {
			tagList:[],
			list:[],
			gid:0,
			keyword:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_group_product&a=taglist&ajax=1",
				dataType:"json",
				success:function(res){
					that.tagList=res.data.list;
				}
			})
			this.getProduct();
		},
		search:function(){
			this.getProduct();
		},
		getProduct:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_group_product&a=product&ajax=1",
				data:{
					gid:this.gid,
					keyword:this.keyword
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		setGid:function(v){
			this.gid=v;
			this.getProduct();
		},
		goToggle:function(item){
			var that=this;
			if(this.gid==0){
				skyToast("请选择标签")
				return false;
			}
			$.ajax({
				url:"/moduleshop.php?m=csc_group_product&a=toggle&ajax=1",
				dataType:"json",
				data:{
					gid:this.gid,
					productid:item.id
				},
				success:function(res){
					if(res.data.action=='insert'){
						item.ingroup=1;
					}else{
						item.ingroup=0;
					}
					skyToast(res.message)
				}
			})
		}
	}
})