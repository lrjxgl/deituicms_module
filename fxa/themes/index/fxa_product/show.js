var app=new Vue({
	el:"#orderApp",
	data:function(){
		return {
			list:[],
			tmpList:[],
			timer:0
		}
	},
	created:function(){
		var that=this;
		this.getPage();
 
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxa_product&a=order&ajax=1&productid="+productid,
				dataType:"json",
				success:function(res){
					that.tmpList=res.data.list;
					that.timer=setInterval(function(){
						if(that.tmpList.length>0){
							that.list.push(that.tmpList.shift());
						}else{
							clearInterval(that.timer);
							
							setTimeout(function(){
								that.list=Array();
								that.getPage();
							},5000)
							
						}
						
					},600)
					 
				}
			})
		}
	}
})