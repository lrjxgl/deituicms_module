var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			ischeck:0,
			data:{},
			hbuser:{},
			money:0,
			showHongbao:false,
			showShare:false,
			canGet:false
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=hongbao_day&ajax=1&token="+token,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.ischeck=res.data.ischeck;
					that.data=res.data.data;
					that.hbuser=res.data.hbuser;
					that.canGet=res.data.canGet;
					if(that.ischeck){
						wxshare_title="我在得推网签到,获得了"+res.data.data+"元现金红包,你也来试试吧";
					}
					
				}
			})
		},
		getHongbao:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=hongbao_day&a=get&ajax=1",
				dataType:"json",
				success:function(res){
					
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.money=res.data.money;
					that.showHongbao=true;
					that.getPage();
				}
			})
		},
		share:function(){
			wxshare_title="我在得推网签到,获得了"+this.money+"元现金红包,你也来试试吧";
			
			this.showShare=true;
			this.showHongbao=false;
			
		},
		confirm:function(){
			var that=this;
			this.showHongbao=false;
		},
		goUser:function(userid){
			window.location="/index.php?m=home&userid="+userid
		}
	}
	
})