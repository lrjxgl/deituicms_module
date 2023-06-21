var App=new Vue({
	el:"#app",
	data:function(){
		return {
			pageLoad:false,
			addrid:0,
			pps:[1,2,3,4],
			usernum:1,
			line:{},
			addr:{},
			ppList:[],
			ppitem:{},
			totalmoney:0
		}
	},
	created:function(){
		this.addrid=addrid;
		this.getPage();
	},
	watch:{
		usernum:function(n,o){
			if(n>4){
				this.usernum=4;
				
			}
			this.getMoney();
		}
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_easy&ajax=1",
				data:{
					addrid:this.addrid,
				},
				dataType:"json",
				success:function(res){
					that.line=res.data.line;
					that.addr=res.data.addr;
					that.ppList=res.data.ppList;
					that.getMoney();
				}
			})
		},
		orderSubmit:function(){
			var that=this;
			
			if(!postCheck.canPost()){
				return false;
			}
			if(that.usernum>4){
				skyJs.toast("乘客不能大于4人");
				return false;
			}
			skyJs.confirm({
				content:"确认下单吗",
				success:function(){
					
					$.ajax({
						url:"/module.php?m=pinche_order&a=order&ajax=1",
						dataType:"json",
						data:{
							usernum:that.usernum,
							lineid:that.line.lineid,
							start_addrid:that.addr.addrid,
							totalmoney:that.totalmoney,
							ppid:that.ppitem.ppid
						},
						type:"POST",
						success:function(res){
							 
							if(res.error){
								skyToast(res.message);
							}else{
								if(res.data.action=='finish'){
									skyToast("下单成功");
									//window.location="/module.php?m=pinche_order&a=my";
								}else{
									window.location=res.data.payurl;
								}
								
							}
							
						}
					})
				}
			})
			
		},
		getMoney:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_order&a=getmoney&ajax=1",
				dataType:"json",
				data:{
					usernum:that.usernum,
					lineid:that.line.lineid,
					start_addrid:that.addrid
				},
				success:function(res){
					if(res.error){
						skyToast(res.message);
					}else{
						that.totalmoney=res.data.total_money;
					}
					
				}
			})
		}
	}
})