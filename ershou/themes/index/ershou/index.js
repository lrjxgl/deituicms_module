Vue.component("page-index",{
	 
	data:function(){
		return {
			data:[],
			list:[],
			tab:"find",
			ypList:"",
			flashList:[],
			goldGoods:[],
			adRecycle:[],
			type:"find",
			isFirst:true,
			per_page:0,
			tagname:'',
			tagList:['电脑','钓竿','手机']
		}
	},
	created:function(){
		var that=this;
		this.getPage();
		this.getProduct();
 
	},
	methods:{
		setTab:function(t,w){
			this.tab=t;
			switch(t){
				case "find":
				case "new":
				case "buy":
					this.type=t;
					this.tagname='';
					break;
				default:
					this.type='';
					this.tagname=t;
					break;
				
			}
			this.isFirst=true;
			this.per_page=0;
			this.getProduct();
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou&ajax=1",
				dataType:"json",
				success:function(res){
					that.ypList=res.data.ypList; 
					that.flashList=res.data.flashList;
					that.goldGoods=res.data.goldGoods; 
					that.adRecycle=res.data.adRecycle;
				}
			})
		},
		goLink1:function(item){
			window.location=item.link1
		},
		goGoods:function(id){
			window.location="/module.php?m=gold_product&a=show&id="+id
		},
		getProduct:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ershou_product&ajax=1",
				dataType:"json",
				data:{
					per_page:this.per_page,
					type:this.type,
					tagname:this.tagname
				},
				success:function(res){
					that.list=res.data.list;
					 
				}
			})
		},
		goProduct:function(productid){
			window.location="/module.php?m=ershou_product&a=show&productid="+productid
		}
	},
	template:`
	<div>
		<div class="pd-10 ">
			<div class="flex mgb-10">
				<div class="flex-1">
					<div class="row-box">
						<div class="sbTitle-h1">精选推荐</div>
						<div class="mgb-10 cl3">正品保障 | 品质包退</div>
						<div style="background-color: #fafafa; padding: 10px;">
							<div class="flex flex-wrap">
								 
								<div v-for="(item,index) in ypList" :key="index"  class="col2">
									<img @click="goLink1(item)" :src="item.imgurl" class="wall" />
									<div class="cl2">{{item.title}}</div>
								</div>
							 
								 
							</div>
						</div>
						
					</div>
				</div>
				<div class="flex-1">
					<div class="row-box" style="height: 100%;">
						<div class="sbTitle-h1">旧物换礼</div>
						<div class="mgb-10 cl3">正品保障 | 品质包退</div>
						<div style="background-color: #fafafa; flex:1; padding: 10px; justify-content: space-between;">
							<div class="flex">
								 
								<div @click="goGoods(item.id)" v-for="(item,index) in goldGoods" :key="index" class="flex-1">
									<img :src="item.imgurl+'.100x100.jpg'" class="wall" />
									<div class="cl2">仅剩{{item.total_num}}件</div>
								</div>
								 
								 
							</div>
							<div class="flex-1"></div>
							<div v-if="Object.keys(adRecycle).length>0" gourl="/module.php?m=recycle" class="flex">
								<img @click="goLink1(adRecycle[0])" :src="adRecycle[0].imgurl"   class="wall" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--
			<div class="mgb-10">
				<img src="{$skins}/demo/temai.jpg" class="wall" />
			</div>
			-->
			<div>
				<div class="swiper-container" style="width:100%;" id="indexFlash">
					<div class="swiper-wrapper">
						 
						<div v-for="(item,index) in flashList" :key="index" class="swiper-slide">
							<img @click="goLink1(item)" :src="item.imgurl" class="wmax" />
						</div>
						 
					</div>
				
					<div class="swiper-pagination"></div>
				
				</div>
			</div>
			
			<div>
				<div class="proNav">
					<div @click="setTab('find')" :class="tab=='find'?'proNav-item-active':''" class="proNav-item">猜你喜欢</div>
					<div @click="setTab('new')" :class="tab=='new'?'proNav-item-active':''"   class="proNav-item">最新发布</div>
					 
					<div v-for="(tag,tagIndex) in tagList" :key="tagIndex" @click="setTab(tag)" :class="tab==tag?'proNav-item-active':''"   class="proNav-item">{{tag}}</div>
				 
				</div>
			</div>
			<div v-if="list.length==0" class="emptyData">暂无数据</div>
			<div v-else>
				<product-list :dlist="list"></product-list>
				<div class="loadMore" v-if="per_page>0" @click="getList">加载更多</div>
			</div>
			
			<!--main-body-->
			
		</div>
	</div>
	`
})