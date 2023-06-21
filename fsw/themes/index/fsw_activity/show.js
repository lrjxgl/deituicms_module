var App=new Vue({
	el:"#App",
	data:function(){
		return {
			joinModal:false,
			addr:{},
			actid:0,
			joinList:[],
			fswUser:{},
			join:{},
			showModal:false,
		}
	},
	created:function(){
		this.actid=actid;
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=fsw_activity&a=show&ajax=1",
				data:{
					actid:that.actid
				},
				dataType:"json",
				success:function(res){
					that.act=res.data.data;
					that.joinList=res.data.joinList;
					that.fswUser=res.data.fswUser;
				}
			})
		},
		joinSubmit:function(){
			var that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=fsw_join&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					actid:actid,
					nickname:that.fswUser.nickname,
					telephone:that.fswUser.telephone
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.joinModal=false;
					that.getPage();
				}
			})
		},
		showJoin:function(item){
			var that=this;
			that.join=item;
			that.showModal=true;
		}
		
	}
})
