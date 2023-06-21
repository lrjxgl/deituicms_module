var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:true,
			searchList:[],
			keyword:"",
			searchClass:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		search:function(){
			var that=this;
			that.searchClass="display:block;";
			$.ajax({
				url:"/module.php?m=exam_topic&ajax=1",
				data:{
					keyword:that.keyword
				},
				dataType:"json",
				success:function(res){
					that.searchList=res.data.list;
					 
				}
			})
		},
		searchMy:function(){
			var that=this;
			that.searchClass="display:block;";
			$.ajax({
				url:"/module.php?m=exam_topic&a=my&ajax=1",
				data:{
					keyword:that.keyword
				},
				dataType:"json",
				success:function(res){
					that.searchList=res.data.list;
					 
				}
			})
		},
		searchHide:function(){
			this.searchClass="";
		},
		
		add:function(form){
			var that=this;
			$.ajax({
				url:"/module.php?m=exam_ask&a=save&ajax=1&exid="+exid,
				type:"POST",
				data:$(form).serialize(), 
				dataType:"json",
				success:function(res){
					skyToast(res.message);
					if(res.error){
						
						return false;
					}
					that.getPage();
				}
			})
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=exam_ask&a=list&ajax=1&exid="+exid,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		upExam:function(id){
			var that=this;
			$.ajax({
				url:"/module.php?m=exam_ask&a=save&ajax=1&id="+id,
				dataType:"json",
				type:"POST",
				data:{
					exid:$("#ex"+id).find("[name='exid']").val(),
					id:id,
					orderindex:$("#ex"+id).find("[name='orderindex']").val(),
					grade:$("#ex"+id).find("[name='grade']").val()
				},
				success:function(res){
					 skyToast(res.message);
				}
			})
		},
		del:function(id){
			var that=this;
			if(confirm("确认删除吗?")){
				$.ajax({
					url:"/module.php?m=exam_ask&a=delete&ajax=1&id="+id,
					dataType:"json",
					success:function(res){
						 if(!res.error){
							 var list=that.pageData.list;
							 var newlist=[];
							 for(var i=0;i<list.length;i++){
								 if(list[i].id!=id){
									 newlist.push(list[i]);
								 }
							 }
							 that.pageData.list=newlist;
						 }
					}
				})
			}
			
		}
	}
})