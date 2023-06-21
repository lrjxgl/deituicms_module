var App=new Vue({
 
	el:"#app",
	data:function(){
		return {
			data:{},
			typelist:{},
			typeid:1,
			addrlist:{},
			addrClass:"",
			addrType:1,
			addrSong:{},
			addrQu:{},
			fromaddrid:0,
			toaddrid:0,
			catList:{},
			catid:0,
			money:10,
			user:{},
			userBoxClass:"",
			dotime:"",
			sender_num:10,
			city:"厦门",
			cityid:0,
			pconfig:{}
		}
	},
	created:function(){
		this.getPage();
		this.getAddr();
		if(localStorage.getItem("city")){
			this.city=localStorage.getItem("city");
			this.cityid=localStorage.getItem("cityid");
		}	
		
	},
	watch:{
		typeid:function(e){
			console.log(e);
			if(e==5){
				setTimeout(function(){
					laydate.render({
					  elem: '#dotime',
					  type: 'datetime'
					});
				},300)
				
			}
		}
	},
	methods:{
		setCat:function(item){
			this.catid=item.catid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui&a=add&ajax=1",
				data:{
					typeid:this.typeid
				},
				dataType:"json",
				success:function(res){
					that.typelist=res.data.typelist;
					that.typeid=res.data.typeid;
					that.catList=res.data.catList;
					that.user=res.data.user;
					that.sender_num=res.data.sender_num;
					that.pconfig=res.data.pconfig;
					that.money=that.pconfig.min_money;
				}
			})
		},
		showUserBox:function(){
			this.userBoxClass="flex-col";
		},
		setType:function(typeid){
			this.typeid=typeid;
			this.catid=0;
		},
		getAddr:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=paotui_addr&ajax=1&type=online",
				dataType:"json",
				success:function(res){
					that.addrlist=res.data.list;
				}
			})
		},
		showAddr:function(type){
			this.addrClass="flex";
			this.addrType=type;
		},
		setAddr:function(addr){
			if(this.addrType==1){
				this.addrSong=addr;
				this.fromaddrid=addr.addrid;
			}else{
				this.addrQu=addr;
				this.toaddrid=addr.addrid;
			}
			console.log(this.addrSong);
			this.addrClass="";
		},
		submit:function(){
			var that=this;
			skyJs.confirm({
				content:"确认发布赏金"+this.money+"元的任务吗",
				success:function(){
					if(!postCheck.canPost()){
						return false;
					}
					$.ajax({
						url:"/module.php?m=paotui&a=save&ajax=1",
						data:$("#form").serialize(),
						method:"post",
						dataType:"json",
						success:function(res){
							skyToast(res.message);
							if(res.error) return false;
							if(res.data.action=="success"){
								setTimeout(function(){
									window.location.reload();
								},1000)
								
							}else{
								window.location=res.data.payurl;
							}
							
						}
					})
				}
			})
			 	
			
		}
	}
})