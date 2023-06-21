Vue.component('log-list',{
	 
	data:function(){
		return {
			pageLoad:false,
			list:[],
			per_page:0,
			isFirst:true,
			catid:0 
		}
	},
	created:function(){
		 
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=my&ajax=1",
				data:{
					catid:this.catid
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
					that.pageLoad=true;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=my&ajax=1",
				data:{
					catid:this.catid,
					per_page:that.per_page
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
		},
		goLog:function(item){
			this.$emit("call-parent",item)
		},
		del:function(item){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_bchat&a=delete&ajax=1",
				dataType:"json",
				data:{
					id:item.id
				},
				success:function(res){
					var list=[];
					for(var i in that.list){
						if(that.list[i].id!=item.id){
							list.push(that.list[i])
						}
					}
					that.list=list;
				}
			})
		}
	},
	template:`
		<div>
			<div class="bd-mp-10 flex" v-for="(item,index) in list" :key="index">
				<div class="flex-1 pointer">
					{{item.prompt}}
				</div>	
				<div  @click="goLog(item)" class="btn-small mgr-5">查看</div>
				<div  @click="del(item)" class="btn-small btn-danger">删除</div>
				
			</div>
			<div class="loadMore" v-if="per_page>0" @click="getList()">加载更多</div>
		</div>
	`
})