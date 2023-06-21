var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			type:"check"
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setType:function(t){
			this.type=t;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=exue_course_teacher&a=list&ajax=1&courseid="+courseid,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		updateItem:function(item){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=exue_course_teacher&a=save&ajax=1&courseid="+courseid,
				dataType:"json",
				data:{
					tcid:item.tcid,
					ischeck:item.ischeck
				},
				success:function(res){
					that.getPage();
				}
			})
		}
	}
})

 