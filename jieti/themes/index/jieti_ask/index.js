var app=new Vue({
		el:"#App", 
		data() {
			return {
				list:"",
				pageLoad:"", 
			};
		},
		created:function(){
			this.getPage();
		},
		methods:{
			getPage:function(){
				var that=this;
				$.ajax({
					url:"/module.php?m=jieti_ask&ajax=1",
					dataType:"json",
					success:function(res){
						that.pageLoad=true;
						that.list=res.data.list;
						 
					}
				})
			}
		}
	}
)	