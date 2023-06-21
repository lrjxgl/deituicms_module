var app=new Vue({
	el:"#App",
	data:function(){
		return {
			catList:[],
			aItem:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_category&ajax=1",
				dataType:"json",
				success:function(res){
					that.catList=res.data.catList;
					that.aItem=res.data.catList[Object.keys(res.data.catList)[0]];
					console.log(that.aItem)
				}
			})
		},
		setCat:function(catid){
			this.aItem=this.catList[catid];
		},
		goList:function(catid){
			window.location="/module.php?m=csc_product&a=list&catid="+catid;
		}
	}
})