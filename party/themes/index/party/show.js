$(function(){
	$(document).on("click","#actJoin",function(){
		$("#actBox").show();
	})
	
	$(document).on("click","#actSubmit",function(){
		if(!postCheck.canPost()){
			return false;
		}
		$.ajax({
			url:"/module.php?m=party_join&a=save&ajax=1",
			type:"POST",
			dataType:"json",
			data:$("#actForm").serialize(),
			success:function(res){
				skyJs.toast(res.message);
				if(!res.error){
					$("#actBox").hide();
					if(res.data.action=="pay"){
						window.location=res.data.payurl;
					}
				}
			}
		})
	})
})
var App=new Vue({
	el:"#App",
	data:function(){
		return {
			party:{},
			joinList:[],
			join:{},
			blogList:[]
		}
	},
	created:function(){
		this.getPage();
		this.getJoin();
		this.getBlog();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=party&a=show&ajax=1",
				dataType:"json",
				data:{
					id:pid
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.party=res.data.data;
					that.join=res.data.join;
				}
			})
		},
		
		getJoin:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=party_join&ajax=1",
				dataType:"json",
				data:{
					pid:pid
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.joinList=res.data.list;
				}
			})
		},
		 
		getBlog:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=party_blog&a=list&type=new&ajax=1",
				dataType:"json",
				data:{
					partyid:pid
				},
				success:function(res){
					if(res.error){
						return false;
					}
					that.blogList=res.data.list;
				}
			})
		},
		goBlog:function(id){
			window.location="/module.php?m=party_blog&a=show&id="+id;
		},
	}
})