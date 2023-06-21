
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			joinList:[],
			per_page:0,
			isFirst:true,
			cjid:0,
			showJoin:false,
			addr:{},
			data:{},
			win_item:{},
			win_user:{},
			itemList:[],
			win_money:0
		}
	},
	created:function(){
		this.cjid=cjid;
		this.getPage();
		this.getOrderList();
		this.getAddr();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ttcj&a=show&ajax=1&cjid="+that.cjid,
				dataType:"json",
				success:function(res){
					that.data=res.data.data;
					that.win_user=res.data.win_user;
					that.itemList=res.data.itemList;
					that.win_money=res.data.win_money;
					that.win_item=res.data.win_item;
				}
			})
		},
		getOrderList:function(){
			var that=this;
			if(!that.isFirst && that.per_page==0){
				return false;
			}
			$.ajax({
				url:"/module.php?m=ttcj_join&ajax=1&cjid="+that.cjid,
				dataType:"json",
				success:function(res){
					that.joinList=res.data.list;
				}
			})
		},
		getAddr:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ttcj&a=addr&ajax=1",
				dataType:"json",
				success:function(res){
					if(res.error){
						skyToast(res.message);
							
						return false;
					}
					that.addr=res.data.addr;
				}
			})
		},
		joinSubmit:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ttcj_join&a=join&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					cjid:that.cjid,
					nickname:that.addr.nickname,
					telephone:that.addr.telephone,
					address:that.addr.address
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){	
						return false;
					}
					that.showJoin=false;
					that.getPage();
					that.getOrderList()
				}
			})
		}
	}
})
