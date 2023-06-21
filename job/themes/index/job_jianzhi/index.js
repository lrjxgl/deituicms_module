var App=new Vue({
	el:"#App",
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0,
			catList:[],
			cat_label:"类别",
			tabCatShow:false,
			tabMoneyShow:false,
			money_label:"工资",
			moneyList:[]
			
		}
	},
	created:function(){

		this.getPage();
	},
	methods:{
		goDetail:function(id){
			window.location="/module.php?m=job_jianzhi&a=show&id="+id
		},
		setMoney:function(item){
			this.money_label=item;
			this.tabMoneyShow=false;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		setCat:function(catid,title){
			this.catid=catid;
			this.cat_label=title;
			this.tabCatShow=false;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		getPage:function(){
			var that=this;
			var money=this.money_label=='工资'?'':this.money_label;
			$.ajax({
				url:"/module.php?m=job_jianzhi&ajax=1",
				data:{
					catid:this.catid,
					money_choice:money
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
					that.catList=res.data.catList;
					that.moneyList=res.data.moneyList;
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			var money=this.money_label=='工资'?'':this.money_label;
			$.ajax({
				url:"/module.php?m=job_jianzhi&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page,
					money_choice:money
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
		}
	}
})