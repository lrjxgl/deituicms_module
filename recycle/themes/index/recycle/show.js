var App=new Vue({
	el:"#App",
	data:{
		item:{},
		logList:[],
		raty_content:"",
		raty_grade:8
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setRatyGrade:function(e){
			this.raty_grade=e;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=recycle&a=show&ajax=1",
				data:{
					id:id
				},
				dataType:"json",
				success:function(res){
					that.item=res.data.data;
					that.logList=res.data.logList;
					if(res.data.raty){
						that.raty_grade=res.data.raty.raty_grade;
						that.raty_content=res.data.raty.raty_content;
					}
				}
			})
		},
		
		cancel:function(item){
			var that=this;
			skyJs.confirm({
				title:"确认提示",
				content:"确认取消回收吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=recycle&a=cancel&ajax=1",
						data:{
							id:item.id
						},
						dataType:"json",
						success:function(res){
							
							skyJs.toast(res.message);
							if(!res.error){
								that.getPage();
							}
						}
					})
				}
			})
		},
		ratySubmit:function(){
			var that=this;
			skyJs.confirm({
				title:"确认提示",
				content:"确认评价吗？",
				success:function(){
					$.ajax({
						url:"/module.php?m=recycle_raty&a=save&ajax=1",
						data:{
							recycleid:that.item.id,
							raty_grade:that.raty_grade,
							raty_content:that.raty_content
						},
						type:"POST",
						dataType:"json",
						success:function(res){
							
							skyJs.toast(res.message);
							if(!res.error){
								that.getPage();
							}
						}
					})
				}
			})
		}
	}
})