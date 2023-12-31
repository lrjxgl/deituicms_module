var App=new Vue({
	el:"#App",
	data:function(){
		return {
			placeid:0,
			tab:"activity",
			actList:[],
			orderList:[],
			orderModal:false,
			orderMoney:1,
			orderDesc:"一起贡献爱心",
			account:{},
			accList:[],
			adModal:false,
			admin:{
				nickname:"",
				telephone:"",
				description:""
			}
		}
	},
	created:function(){
		this.placeid=placeid;
		this.getActList();
		this.getOrderList();
		this.getAccount();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		getAccount:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_account&a=get&ajax=1&placeid="+this.placeid,
				dataType:"json",
				success:function(res){
					that.accList=res.data.logList;
					that.account=res.data.account;
				}
			})
		},
		getActList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_activity&a=list&ajax=1&placeid="+this.placeid,
				dataType:"json",
				success:function(res){
					that.actList=res.data.list;
				}
			})
		},
		goActivity:function(item){
			window.location="/module.php?m=fishing_free_activity&a=show&actid="+item.actid
		},
		getOrderList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_order&a=list&ajax=1&placeid="+this.placeid,
				dataType:"json",
				success:function(res){
					that.orderList=res.data.list;
				}
			})
		},
		orderSubmit:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_free_order&a=order&ajax=1&placeid="+this.placeid,
				dataType:"json",
				type:"POST",
				data:{
					money:this.orderMoney,
					description:this.orderDesc
				},
				success:function(res){
					skyToast(res.message)
					 that.orderModal=false;
					 window.location=res.data.payurl;
				}
			})
		},
		adminSubmit:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_free_admin&a=applysave&ajax=1&placeid="+this.placeid,
				dataType:"json",
				type:"POST",
				data:{
					placeid:this.placeid,
					nickname:this.admin.nickname,
					telephone:this.admin.telephone,
					description:this.admin.description
				},
				success:function(res){
					skyToast(res.message)
					that.adModal=false;
					 
				}
			})
		}
	}
})