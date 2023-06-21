var uiApp=new Vue({
	el:"#uiApp",
	data:function(){
		return {
			apiList:[],
			api:"",
			apiKey:"",
			show:false
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=diypage&a=api",
				dataType:"json",
				success:function(res){
					that.apiList=res;
				}
			})
		},
		submit:function(e){
			skyJs.toast("保存成功");
			var len=uiData.length;
			for(var i=0;i<len;i++){
				if(uiData[i].id==uiId){
					uiData[i].api=this.api;
					uiData[i].apiKey=this.apiKey;
					break;
				}
			}
			this.show=false;
		}
	}
})