var App=new Vue({
	el:"#App",
	data:function(){
		return {
			me:{},
			parent:{},
			us:[],
			child:[],
			group:{},
			nickname:""
		}
	},
	created:function(){
		this.getPage()
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_people&a=home&ajax=1",
				dataType:"json",
				data:{
					pid:0,
					gid:0		
				},
				success:function(res){
					that.me=res.data.me;
					that.parent=res.data.parent;
					that.us=res.data.us;
					that.child=res.data.child;
					that.group=res.data.group;
				}
			})
		},
		setParent:function(id){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_people&a=home&ajax=1",
				dataType:"json",
				data:{
					pid:id,
					gid:0		
				},
				success:function(res){
					that.me=res.data.me;
					that.parent=res.data.parent;
					that.us=res.data.us;
					that.child=res.data.child;
				}
			})
		},
		search:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_people&a=home&ajax=1",
				dataType:"json",
				data:{
					 
					nickname:this.nickname		
				},
				success:function(res){
					that.me=res.data.me;
					that.parent=res.data.parent;
					that.us=res.data.us;
					that.child=res.data.child;
				}
			})
		}
	}
})