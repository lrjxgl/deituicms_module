var App=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			myList:[],
			tab:"all",
			addr:{},
			in_orderid:0,
			isAddr:false
		}
	},
	created:function(){
		this.getOrder();
		this.getMy();
		this.getAddr();
	},
	methods:{
		setTab:function(tab){
			
			if(tab=='all'){
				this.getOrder();
			}else{
				this.getMy();
			}
			this.tab=tab;
		},
		getOrder:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=dzp_event&a=order&ajax=1",
				data:{
					eventid:eventid
				},
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		getAddr:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=user_lastaddr&a=my&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.addr=res.data;
				}
			})
		},
		showAddr:function(orderid){
			this.in_orderid=orderid;
			this.isAddr=true;
		},
		hideAddr:function(){
			this.in_orderid=0;
			this.isAddr=false;
		},
		getMy:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=dzp_event&a=myorder&ajax=1",
				data:{
					eventid:eventid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.myList=res.data.list;
				}
			})
		},
		changeAddr:function(){
			var that=this;
			skyJs.confirm({
				content:"请确保正常填写收货地址",
				success:function(){
					$.ajax({
						url:"/module.php?m=dzp_order&a=changeaddr&ajax=1",
						data:{
							orderid:that.in_orderid,
							nickname:that.addr.nickname,
							address:that.addr.address,
							telephone:that.addr.telephone
						},
						type:"POST",
						dataType:"json",
						success:function(res){
							if(res.error){
								return false;
							}
							that.hideAddr();
							that.getMy();
						}
					})
				}
			})
			
		},
		received:function(orderid){
			var that=this;
			that.in_orderid=orderid;
			skyJs.confirm({
				content:"请确认已收到",
				success:function(){
					$.ajax({
						url:"/module.php?m=dzp_order&a=received&ajax=1",
						data:{
							orderid:that.in_orderid
						},
						dataType:"json",
						success:function(res){
							if(res.error){
								return false;
							}
 
							that.getMy();
						}
					})
				}
			})
		}
	}
})

$.ajax({
	url:"/module.php?m=dzp_event&a=show&ajax=1",
	data:{
		eventid:eventid
	},
	dataType:"json",
	success:function(res){
		turnWheel.rewards=res.data.list;
		turnWheel.init();
		turnWheel.draw();
	}
})

// 抽取按钮按钮点击触发事件
$('.wheel-pointer').click(function() {
	// 正在转动，直接返回
	if (turnWheel.bRotate) return;
	turnWheel.bRotate = true;
	$.ajax({
		url:"/module.php?m=dzp_event&a=getindex&ajax=1",
		data:{
			eventid:eventid
		},
		dataType:"json",
		success:function(res){
			if(res.error){
				skyJs.toast(res.message,"error");
				turnWheel.bRotate = false;
				return false;
			}
			var index = res.data.index;

			turnWheel.goRoate(turnWheel.rewards.length - index, function() {
			 
				skyJs.toast(turnWheel.rewards[index].reward_desc);
				App.getOrder();
				 
			}); 
		}
	})
	
});