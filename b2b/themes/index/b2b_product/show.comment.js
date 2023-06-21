var cmVue=new Vue({
	el:"#cmApp",
	data:function(){
		return {
			pageData:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_product&a=raty&ajax=1&limit=1&id="+productid,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		}
	}
})

var recApp=new Vue({
	el:"#recApp",
	data:function(){
		return {
			recList:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=b2b_product&a=detailRecList&ajax=1&limit=1&id="+productid,
				dataType:"json",
				success:function(res){
					if(!res.error){
						that.recList=res.data.recList;
					}
					
				}
			})
		},
		goProduct:function(id){
			window.location="/module.php?m=b2b_product&a=show&id="+id;
			 
		},
	}
})