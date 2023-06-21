var that;
var app=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			data:{},
			lineList:[],
			lineClass:"",
			endList:[],
			activeLine:{},
			start_addr:"",
			startAddr_input:"",
			start_addrid:0,
			start_lat:0,
			start_lng:0,
			end_addr:"",
			end_addrid:0,
			end_lat:0,
			end_lng:0,
			startAddrClass:"",
			endAddrClass:"",
			isMapStart:false,
			usernum:1,
			totalmoney:0,
			ppList:[],
			ppitem:{},
			baiTime:false
		}
	},
	created:function(){
		this.getPage();
		that=this;
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
							lineid:that.activeLine.lineid,
							start_addr:that.start_addr,
							start_lat:that.start_lat,
							start_lng:that.start_lng,
							end_addrid:that.end_addrid,
							end_addr:that.end_addr,
							totalmoney:that.totalmoney,
							ppid:that.ppitem.ppid
						},
						type:"POST",
						success:function(res){
							that.activeLine={};
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
		getPage:function(){		
			$.ajax({
				url:"/module.php?m=pinche&a=indexdata&ajax=1",
				dataType:"json",
				success:function(res){
					that.pageLoad=true;
					that.lineList=res.data.lineList;
					that.ppList=res.data.ppList;
					that.baiTime=res.data.baiTime;
				}
			})
		},
		choiceLine:function(item){
			this.activeLine=item;
			this.lineClass="";
			this.start_addr=item.start_addr;
			this.end_addr=item.end_addr;
			this.totalmoney=item.basemoney;
			 
			this.getAddr()
		},
		getAddr:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_line_addr&ajax=1",
				dataType:"json",
				data:{
					stype:2,
					lineid:this.activeLine.lineid
				},
				success:function(res){
					 
					that.endList=res.data.list;
				}
			})
		},
		startAddrShow:function(){
			this.startAddrClass="flex-col";
			var that=this;
			setTimeout(function(){
				if(!that.isMapStart){
					that.isMapStart=true;
					//initMap();
				}
				
				//mapGps();
			},300)		
		},
		choiceStartAddr:function(str,t){
			if(str=='' && t==1){
				skyToast("请填写地址");
				return false;
			}
			this.start_addr=str;
			this.startAddrClass="";
			if(str!=''){
				this.start_lat=geoLat;
				this.start_lng=geoLng;
			}
			this.getMoney()
		},
		choiceEndAddr:function(item){
			if(item==''){
				this.end_addr="";
				this.end_addrid=0;
			}else{
				this.end_addr=item.addr;
				this.end_addrid=item.addrid;
			}
			
			this.endAddrClass="";
			
			this.getMoney()
		},
		getMoney:function(){
			$.ajax({
				url:"/module.php?m=pinche_order&a=getmoney&ajax=1",
				dataType:"json",
				data:{
					usernum:that.usernum,
					lineid:that.activeLine.lineid,
					start_addr:that.start_addr,
					start_lat:that.start_lat,
					start_lng:that.start_lng,
					end_addrid:that.end_addrid
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