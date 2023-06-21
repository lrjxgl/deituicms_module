var adminid;
var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageData:{},
			pageLoad:false,
			modalClass:"",
			pwd1:"",
			pwd2:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=b2b_admin&ajax=1&shopid="+shopid,
				dataType:"json",
				success:function(res){
					that.pageData=res.data;
					that.pageLoad=true;
				}
			})
		},
		addFormSubmit:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=b2b_admin&a=save&ajax=1&shopid="+shopid,
				data:$("#addForm").serialize(),
				dataType:"json",
				method:"POST",
				success:function(res){
				 
					that.getPage();
				}
			})
		},
		updateSave:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=b2b_admin&a=PasswordSave&ajax=1&shopid="+shopid,
				data:{
					password:this.pwd1,
					password2:this.pwd2,
					adminid:adminid
				},
				dataType:"json",
				method:"POST",
				success:function(res){
					 
					 
					if(res.error){
						skyToast(res.message)
					}else{
						that.modalClose();
					}
					
				}
			})
		},
		modalShow:function(aid){
			this.modalClass="flex";
			adminid=aid;
			this.pwd1="";
			this.pwd2="";
		},
		modalClose:function(){
			this.modalClass="";
		}
	}
})