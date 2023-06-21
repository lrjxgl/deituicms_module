var app=new Vue({
	el:"#App",
	data:function(){
		return {
			industryList:[],
			moduleList:[],
			per_page:0,
			isFirst:true,
			pid:1,
			orderby:""
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		setPid:function(pid){
			this.pid=pid;
			$(window).scrollTop(0);
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke_search&a=category&ajax=1&pid="+this.pid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.industryList=res.data.industryList;
					that.moduleList=res.data.moduleList;
				}
			})
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=taoke_search&a=category&ajax=1&pid="+this.pid,
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					
					that.moduleList=res.data.moduleList;
				}
			})
		},
		goDetail:function(id,word){
			window.location="/module.php?m=taoke_search&a=list&catmap="+encodeURI(id)+"&word="+encodeURI(word);
		}
	}
})