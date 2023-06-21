var App=new Vue({
	el:"#App",
	data:function(){
		return {
				list:[],
				ratyClass:"",
				raty_tid:0,
				 
				raty_grade:0,
				raty_content:""
		}
	
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_user&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		goDetail:function(id){
			window.location="/module.php?m=house_tuan&a=show&id="+id;
		},
		goRaty:function(item){
			var that=this;
			that.raty_tid=item.id;
			$("#ratyModal").show();
		},
		ratySubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=house_tuan_raty&a=save&ajax=1",
				dataType:"json",
				type:"post",
				data:{
					raty_grade:$("[name='v-raty-grade']").val(),
					raty_content:that.raty_content,
					tid:that.raty_tid
				},
				success:function(res){
					skyToast(res.message);
					$("#ratyModal").hide();
					that.getList();
				}
			})
		}
	}	
})