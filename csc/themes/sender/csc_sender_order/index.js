
var app=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:[],
			type:type
		}
	},
	created:function(){
		this.getPage();
		$("#app").show();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/sender.php?m=csc_sender_order&ajax=1",
				data:{
					type:that.type
				},
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
				}
			})
		},
		goOrder:function(ptorderid){
			window.location="/sender.php?m=csc_sender_order&a=show&ptorderid="+ptorderid
		},
		setType:function(t){
			this.type=t;
			this.getPage();
		}
	}
});