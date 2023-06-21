var app=new Vue({
		el:"#App", 
		data() {
			return {
				pageData:"",
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
					url:"/module.php?m=jieti&ajax=1",
					dataType:"json",
					success:function(res){
						that.pageLoad=true;
						that.pageData=res.data;
						 
					}
				})
			},
			goAdd:function(){
				uni.navigateTo({
					url:"../mjieti_ask/add"
				})
			},
			goAsk:function(askid){
				uni.navigateTo({
					url:"../mjieti_ask/show?askid="+askid
				})
			} 
		}
	}
)	