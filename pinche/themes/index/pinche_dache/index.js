var App=new Vue({
	el:"#App",
	data:function(){
		return {
			about:{},
			cheList:[],
			from_addr:"",
			to_addr:"",
			telephone:"",
			user_num:1,
			description:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=pinche_dache&ajax=1",
				dataType:"json",
				success:function(res){
					that.cheList=res.data.cheList;
					that.about=res.data.about;
				}
			})
		},
		orderSubmit:function(){
			var  that=this;
			if(!postCheck.canPost()){
				return false;
			}
			$.ajax({
				url:"/module.php?m=pinche_dache_order&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					from_addr:that.from_addr,
					to_addr:that.to_addr,
					telephone:that.telephone,
					user_num:that.user_num,
					description:that.description,
					
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.from_addr="";
					that.to_addr="";
				}
			})
		}
	}
})