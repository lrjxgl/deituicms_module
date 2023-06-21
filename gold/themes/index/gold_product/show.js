var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			addrModalClass:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gold_product&a=show&ajax=1&id="+id,
				dataType:"json",
				success:function(res){
					 
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		order:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=gold_order&a=order&ajax=1",
				data:{
					id:id,
					address:$("#address").val(),
					telephone:$("#telephone").val(),
					nickname:$("#nickname").val()
				},
				method:"POST",
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					if(!res.error){
						that.getPage();
						that.addrModalClass="";
					}
					
				}
			})
		},
		showAddr:function(){
			this.addrModalClass="flex-col";
		},
		hideAddr:function(){
			this.addrModalClass="";
		}
	}
})