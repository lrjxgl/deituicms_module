var App=new Vue({
	el:"#App",
	data:function(){
		return {
			actid:0,
			tab:"detail",
			joinModal:false,
			isJoin:0,
			joinList:[],
			addr:{},
			data:{}
		}
	},
	created:function(){
		this.actid=actid;
		this.getPage();
		this.getJoinList();
	},
	methods:{
		setTab:function(t){
			this.tab=t;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_activity&a=show&ajax=1",
				data:{
					actid:this.actid
				},
				dataType:"json",
				success:function(res){
					that.addr=res.data.addr;
					that.isJoin=res.data.isJoin; 
					that.data=res.data.data;
				}
			})
		},
		getJoinList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fishing_free_join&a=list&ajax=1",
				data:{
					actid:this.actid
				},
				dataType:"json",
				success:function(res){
					that.joinList=res.data.list;
					 
				}
			})
		},
		joinSubmit:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fishing_free_join&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					actid:this.actid,
					nickname:this.addr.nickname,
					telephone:this.addr.telephone
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.joinModal=false;
				}
			})
		}
	}
})

 