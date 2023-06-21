var App=new Vue({
	el:"#App",
	data:function(){
		return {
			orderid:0,
			order:{},
			course:{},
			shop:{},
			teacher:{},
			bindStudent:false,
			ratyBox:false,
			stList:[],
			stid:0
		}
	},
	created:function(){
		this.orderid=orderid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=exue_order&a=show&ajax=1&orderid="+this.orderid,
				dataType:"json",
				success:function(res){
					that.order=res.data.order;
					that.course=res.data.course;
					that.shop=res.data.shop;
					that.teacher=res.data.teacher;
					that.student=res.data.student;
					that.stList=res.data.stList;
				}
			})
		},
		baodao:function(){
			var that=this; 
			if(!confirm("确认已到学校报到乐吗?")){
				return false;
			}
			$.ajax({
				url:"/module.php?m=exue_order&a=BaoMing&ajax=1",
					 
				dataType:"json",
				data:{
					orderid:this.orderid
				},
				success:function(res){
					skyToast(res.message)
					if(res.error){
						
						return false;
					}
					that.getPage(); 
				}
			})
		},
		bindSubmit:function(){
			var that=this; 
			$.ajax({
				url:"/module.php?m=exue_order&a=bindStudent&ajax=1",
				type:"POST",
				dataType:"json",
				data:{
					orderid:orderid,
					stid:this.stid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message)
						return false;
					}
					that.bindStudent=false;
					that.getPage();
				}
			})
		},
		goShop:function(shopid){
			window.location="/module.php?m=exue_shop&a=show&shopid="+shopid;
		},
		goCourse:function(courseid){
			window.location="/module.php?m=exue_course&a=show&courseid="+courseid;
		},
		goTeacher:function(tcid){
			window.location="/module.php?m=exue_teacher&a=show&tcid="+tcid;
		},
		goStudent:function(stid){
			window.location="/module.php?m=exue_student&a=show&stid="+stid;
		}
	}
})

 