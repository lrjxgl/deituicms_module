var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			listA:[],
			listB:[],
			tabAgeShow:false,
			ageList:[],
			age_label:"年龄",
			tabMoneyShow:false,
			moneyList:[],
			money_label:"收入",
			tabAddrShow:false,
			addrList:[],
			addr_label:"户籍",
			addrid:0
			
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		tabShow:function(t){
			switch(t){
				case "tabAgeShow":
					
					this.tabAgeShow=this.tabAgeShow?false:true;
					this.tabMoneyShow=false;
					this.tabAddrShow=false;
					break;
				case "tabMoneyShow":
					this.tabAgeShow=false;
					this.tabMoneyShow=this.tabMoneyShow?false:true;
					this.tabAddrShow=false;
					break;
				case "tabAddrShow":
					this.tabAgeShow=false;
					this.tabMoneyShow=false;
					this.tabAddrShow=this.tabAddrShow?false:true;
					break;
			}
		},
		setAge:function(item){
			this.age_label=item;
			this.tabAgeShow=false;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		setMoney:function(item){
			this.money_label=item;
			this.tabMoneyShow=false;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		setAddr:function(addrid,title){
			console.log(addrid)
			this.addrid=addrid;
			this.cat_label=title;
			this.tabAddrShow=false;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=xiangqin_people&ajax=1",
				data:{
					catid:this.catid
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
						return false;
					}
					that.list=res.data.list;
					that.parseList()
					that.isFirst=false;
					that.per_page=res.data.per_page;
					that.ageList=res.data.ageList;
					that.moneyList=res.data.moneyList;
					that.addrList=res.data.addrList;
					that.pageLoad=true;
				}
			})
		},
		parseList:function(){
			var listA=[],listB=[];
			for(var i in this.list){
				if(i%2==0){
					listA.push(this.list[i])
				}else{
					listB.push(this.list[i])
				}
			}
			this.listA=listA;
			this.listB=listB;
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			var money=this.money_label=='收入'?'':this.money_label;
			var age=this.age_label=="年龄"?'':this.age_label;
			$.ajax({
				url:"/module.php?m=xiangqin_people&a=list&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					addrid:this.addrid,
					money_choice:money,
					age_choice:age
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
					
					that.parseList()
					that.per_page=res.data.per_page;
					that.pageLoad=true;
				}
			})
		},
		goDetail:function(id){
			if(unJoin){
				skyToast("您还未加入相亲部落，无法查看")
				return false;
			}
			window.location="/module.php?m=xiangqin_people&a=show&id="+id
		}
	}
})