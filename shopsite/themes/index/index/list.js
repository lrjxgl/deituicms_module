Vue.component("list",{
	props:{
		shopid:0
	},
	data:function(){
		return {
			catList:[],
			list:[],
			catid:0,
			isFirst:true,
			per_page:0,
			type:'',
			catClass:'',
			cat_label:"全部商品",
			globalData:globalData
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		setCat:function(catid,cat_label){
			this.catid=catid;
			this.catClass="";
			this.isFirst=true;
			this.per_page=0;
			this.cat_label=cat_label
			this.getList();
		},
		setProType:function(type){
			this.type=type;
			this.isFirst=true;
			this.per_page=0;
			this.getList();
		},
		showCategory:function(){
			this.catClass="flex-col"
		},
		goProduct:function(id,shopid){
			window.location="/module.php?m=shopsite_product&a=show&id="+id+"&shopid="+shopid;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=shopsite&a=product&ajax=1&shopid="+this.globalData.shopid,
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
					that.catList=res.data.shop_catlist;
					that.per_page=res.data.per_page;
					that.isFirst=false;
				}
			})
		},
		getList:function(){
			var that=this;
			if(that.per_page==0 && !that.isFirst){
				return false;
			}
			$.ajax({
				url:"/module.php?m=shopsite&a=product&ajax=1&shopid="+this.globalData.shopid,
				data:{
					catid:that.catid,
					per_page:that.per_page,
					type:that.type
				},
				dataType:"json",
				success:function(res){
					if(res.error){
						return false;
					}
					that.per_page=res.data.per_page;
					if(that.isFirst){
						that.list=res.data.list;
						that.isFirst=false;
					}else{
						for(var i in res.data.list){
							that.list.push(res.data.list[i]);
						}
					}
					
				 
					 
				}
			})
		},
	},
	template:`
	<div>
		<div class="bg-white text-center flex flex-ai-center flex-jc-center pd-10">
			<div class="iconfont icon-cascades cl-num f14 mgr-5"></div>
			<div @click="showCategory" class="cl-money f14 pointer">{{cat_label}}</div>
		</div>
		
		<div :class="catClass" class="modal-group">
			<div @click="catClass=''" class="modal-mask"></div>
			<div class="modal">
				<div class="modal-body">
					<div @click="setCat(0,'全部商品')" class="row-item">
						<div class="row-item-title">全部商品</div>
					</div>
					<div class="row-item" @click="setCat(cat.catid,cat.title)" v-for=" (cat,index) in  catList" :key="index">
						<div class="row-item-title">{{cat.title}}</div>							
					</div>
				</div>
			</div>
		</div>
		 
		
		<div class="mtlist">
			<div class="mtlist-item" v-for="(item,index) in list" :key="index">
				<div class="mtlist-item-bd">
					<img @click="goProduct(item.id,item.shopid)" class="mtlist-img pointer bd-radius-5" :src="item.imgurl+'.small.jpg'" />
					<div class="mtlist-item-pd">
						<div @click="goProduct(item.id,item.shopid)" class="mtlist-title pointer">{{item.title}}</div>
						 
						<div class="flex flex-ai-center mgb-5">
							<div class="cl-money f12">￥</div>
							<div class="cl-money f14 mgr-5">{{item.price}}</div>
							 
							<div class="flex-1"></div>
							
						</div>
						 
					</div>
				</div>
			
			</div>
		</div>
		<div class="loadMore" v-if="per_page>0" @click="getList()">加载更多</div>
	</div>		
	`
})