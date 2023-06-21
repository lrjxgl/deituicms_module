var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			tab:"",
			orderby:"",
			orderName:"综合",
			qk_choice:"",
			choice_min_price:0,
			choice_max_price:0,
			choice_day:0,
			provinceList:[],
			province:{},
			cityList:[],
			city:{},
			townList:[],
			town:{},
			areaName:"区域",
			addr_upid:0,
			addrHeight:400,
			addr:{}
		}
	},
	created:function(){
		this.catid=catid;
		this.getPage();
		this.getCityList(0);
		this.ipCity();
	},
	methods:{
		ipCity:function(){
			var that=this;
			$.ajax({
				url:"/index.php?m=lbs&a=ipcity&ajax=1",
				dataType:"json",
				success:function(res){
					that.addr={
						title:res.data.region+"·"+res.data.city,
						value:{
							provinceid:0,
							cityid:0,
							townid:0
						}
					}
				}
			})
		},
		choiceReset:function(){
			this.qk_choice="";
			this.choice_min_price=0;
			this.choice_max_price=0;
			this.choice_day=0;
			 
		},
		choiceSubmit:function(){
			this.tab='';
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		set_qk_choice:function(t){
			this.qk_choice=t;
		},
		set_choice_day:function(t){
			this.choice_day=t;
		},
		setTab:function(t){
			if(this.tab==t){
				this.tab=''
			}else{
				this.tab=t;
			}
			if(this.tab=='new'){
				this.isFirst=true;
				this.per_page=0;
				this.orderby="new"
				this.getList();
			}
		},
		setOrder:function(t,orderName){
			this.orderName=orderName;
			this.orderby=t;
			this.isFirst=true;
			this.per_page=0;
			this.tab='';
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					orderby:this.orderby,
					qk_choice:this.qk_choice,
					choice_min_price:this.choice_min_price,
					choice_max_price:this.choice_max_price,
					choice_day:this.choice_day,
					
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			var cityid=0;
			 
			if(this.addr.value.townid!=0){
				cityid=this.addr.value.townid;
			}else if(this.addr.value.cityid!=0){
				cityid=this.addr.value.cityid;
			}else if(this.addr.value.provinceid!=0){
				cityid=this.addr.value.provinceid;
			}  
			 
			$.ajax({
				url:"/module.php?m=ershou_product&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					orderby:this.orderby,
					qk_choice:this.qk_choice,
					choice_min_price:this.choice_min_price,
					choice_max_price:this.choice_max_price,
					choice_day:this.choice_day,
					cityid:cityid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
					
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		getCityList:function(up,level){
			if(level==undefined){
				level=0;
			}
			var that=this;
			$.ajax({
				url:"/index.php?m=district&a=list&ajax=1",
				data:{
					upid:up.id,				
				},
				dataType:"json",
				success:function(res){
					if(level==0){
						that.provinceList=res.data.list;
						that.province={};
						that.city={};
						that.town={}
						that.cityList=[];
						that.townList=[];
					}else if(level==1){
						that.cityList=res.data.list;
						
						that.province=up;
						that.city={};
						that.town={}
						that.townList=[];
					}else if(level==2){
						that.city=up;
						that.town={}
						that.townList=res.data.list;
					}
				}
			})
		},
		setAddr:function(){
			var p=c=t="";
			var pid=cid=tid=0;
			this.areaName="区域";
			if(Object.keys(this.province).length>0){
				p=this.province.name;
				pid=this.province.id;
				this.areaName=p;
			}
			if(Object.keys(this.city).length>0){
				c=this.city.name;
				cid=this.city.id;
				this.areaName=c;
			}
			if(Object.keys(this.town).length>0){
				t=this.town.name;
				tid=this.town.id;
				this.areaName=t;
			}
			this.addr={
				title:p+"·"+c+"·"+t,
				value:{
					provinceid:pid,
					cityid:cid,
					townid:tid
				}
			}
			this.tab='';
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		}
	}
})