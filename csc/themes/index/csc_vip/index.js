var app=new Vue({
	el:"#App",
	data:function(){
		return {
			list:[],
			vipid:0,
			vip_day:0,
			money:0,
			myvip:{}
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=csc_vip&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.myvip=res.data.myvip;
				}
			})
		},
		setVip:function(c,t,m){
			this.vipid=c;
			this.vip_day=t;
			this.money=m;
		},
		buy:function(){
			var that=this;
			if(this.vipid==0){
				skyToast("请先选择vip类型")
				return false;
			}
			$.ajax({
				url:"/module.php?m=csc_vip&a=buy&ajax=1",
				dataType:"json",
				method:"post",
				data:{
					vipid:this.vipid,
					vip_day:this.vip_day,
					money:this.money
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					if(res.data.action=="success"){
						window.location="/module.php?m=csc_paotui&a=success";
					}else{
						window.location=res.data.payurl;
					}
					
				}
			})
		}
	}
})