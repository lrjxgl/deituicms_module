var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			sds:[],
			senderid:0
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=csc_order_send&a=api&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.sds=res.data.sds;
					console.log(that.sds)
				}
			})
		},
		setSender:function(v){
			this.senderid=v;
		},
		fenpei:function(orderid){
			var that=this;
			if(this.senderid==0){
				skyToast("请选择送货员")
				return false;
			}
			$.ajax({
				url:"/moduleshop.php?m=csc_order_send&a=fenpei&ajax=1",
				dataType:"json",
				data:{
					orderid:orderid,
					senderid:this.senderid
				},
				success:function(res){
					that.getPage(); 
				}
			})
		}
	}
})