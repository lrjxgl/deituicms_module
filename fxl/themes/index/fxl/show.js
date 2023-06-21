var App=new Vue({
	el:"#App",
	data:function(){
		return {
			fxl:{},
			cert:{},
			logList:[],
			orderList:[],
			cwList:[]
		}
		
	},
	created:function(){
		this.getPage();
		this.getLogList();
		this.getOrderList();
		this.getCwList();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxl&a=show&ajax=1&fxlid="+fxlid,
				dataType:"json",
				success:function(res){
					that.fxl=res.data.data;
					that.cert=res.data.cert;
				}
			})
		},
		getLogList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxl_log&a=list&ajax=1&fxlid="+fxlid,
				dataType:"json",
				success:function(res){
					that.logList=res.data.list;
				}
			})
		},
		getCwList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=bill_log&ajax=1&shopid="+bill_shopid,
				dataType:"json",
				success:function(res){
					that.cwList=res.data.list;
				}
			})
		},
		getOrderList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fxl_order&a=list&ajax=1&fxlid="+fxlid,
				dataType:"json",
				success:function(res){
					that.orderList=res.data.list;
				}
			})
		},
		orderSubmit:function(){
			var money=$("#join-money").val();
			if(money==""){
				skyToast("请输入金额");
				return false;
			}
			var nickname=$("#join-nickname").val();
			if(nickname==""){
				skyToast("请输入捐赠人名字");
				return false;
			}
			$.post("/module.php?m=fxl_order&a=order&ajax=1",{
				fxlid:fxlid,
				money:money,
				nickname:nickname
			},function(data){
				if(data.error){
					return false;
				}
				skyToast(data.message);
				if(data.data.gopay){
					window.location=data.data.url;
				}
				
				
			},"json")
		}
	}
})
 $(window).scroll(function(e){
	 var ex=$("#tabDot").offset().top;
	 var st=$(window).scrollTop()+60;
	 console.log(st,ex);
	 if(st>ex){
		 $("#tabBox").addClass("tabFixed")
	 }else{
		  $("#tabBox").removeClass("tabFixed")
	 }
	 
 })