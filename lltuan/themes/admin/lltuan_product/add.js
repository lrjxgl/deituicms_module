var setApp=new Vue({
	el:"#setApp",
	data:function(){
		return {
			setList:[],
			productid:0,
			new_user_num:0,
			new_price:0
		}
	},
	
	created:function(){
		if(productid!=''){
			this.productid=productid;
			this.getPage();
		}
	},
	watch:{
		setList:function(n,o){
			//console.log(JSON.stringify(n))
			$("#setdata").val(JSON.stringify(n));
		}
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleadmin.php?m=lltuan_product&a=add&ajax=1",
				data:{
					productid:this.productid
				},
				dataType:"json",
				success:function(res){
					that.setList=res.data.setList;
				}
			})
		},
		add:function(){
			this.setList.push({
				user_num:this.new_user_num,
				price:this.new_price
			})
			this.new_user_num=0;
			this.new_price=0;
		},
		del:function(item){
			var list=[];
			for(var i in this.setList){
				if(this.setList[i]!=item){
					list.push(this.setList[i])
				}
			}
			this.setList=list;
		}
	}
})