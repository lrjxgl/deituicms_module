Vue.component("order-list",{
	props:{
		eventid:0
	},
	data:function(){
		return {
			list:[]
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/moduleshop.php?m=bzy_order&ajax=1",
				dataType:"json",
				data:{
					eventid:this.eventid
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		}
	},
	template:`
		<div class="list">
			<div v-for="(item,index) in list" :key="index" class="row-box mgb-5">
				<div class="flex">
					<div class="cl-status">{{item.status_name}}</div>
					<div class="flex-1"></div>
					<div class="cl3">{{item.time}}</div>
				</div>
				<div class="flexlist-item">
					<img class="flexlist-img" :src="item.product.imgurl+'.100x100.jpg'" />
					<div class="flex-1">
						<div class="flexlist-title">{{item.product.title}}</div>
						<div class="flex mgb-5">
							<div class="mgr-10 cl2">联系人：{{item.nickname}}</div>
							<div class="cl-primary">电话：{{item.telephone}}</div> 
						</div>
						<div class="cl3">{{item.address}}</div>
					</div>
				</div>
			</div>
		</div>
	`
})