var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			ptcomList:[],
			addForm:false,
			addNickname:"",
			addHao:"",
			addCom:""
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp_pts&a=my&ajax=1",
	 
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
					that.ptcomList=res.data.ptcomList;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=zbtao_pp_pts&a=my&ajax=1",
				data:{
				 
					per_page:that.per_page
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		addSubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp_pts&a=save&ajax=1",
				dataType:"json",
				type:"post",
				data:{
					nickname:this.addNickname,
					zbhao:this.addHao,
					ptcom:this.addCom
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.addForm=false;
					that.getPage();
				}
			})
		},
		del:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=zbtao_pp_pts&a=delete&ajax=1",
				dataType:"json",
		 
				data:{
					ptid:item.ptid
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.getPage();
				}
			})
		}
	}
})
