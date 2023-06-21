Vue.component("pubu-list",{
	props:{
		dataList:[]
	},
	data:function(){
		return {
			listA:[],
			listB:[]
		}
	},
	created:function(){
		this.parseList(this.dataList)
	},
	watch:{
		dataList:function(n,o){
			this.parseList(n)
		}
	},
	methods:{
		parseList:function(list){
			var listA=[];
			var listB=[];
			for(var i in list){
				if(i%2==0){
					listA.push(list[i])
				}else{
					listB.push(list[i])
				}
			}
			this.listA=listA;
			this.listB=listB;
		},
		goProduct:function(id){
			window.location="/module.php?m=b2b_product&a=show&id="+id;
			 
		},
	},
	template:`
		<div class="flex">
			<div class="flex-1 mgr-5">
				 
						<a  @click="goProduct(item.id)" v-for="(item,index) in listA" :key="index"  class="sglist-item bd-radius-10">
							 
								<img class="sglist-img " :src="item.imgurl+'.small.jpg'" />
								<div class="sglist-item-pd">
									<div class="sglist-title">{{item.title}}</div>
									<div class="flex mgb-5">
										<div class="cl-money">￥</div>
										<div class="cl-money f16">{{item.price}}</div>
										<div class="flex-1"></div>
										<div class="cl2">月销{{item.month_buy_num}}件</div>
									</div>
									
									 
								</div>
							 
						</a>
				 
			</div>
			<div class="flex-1">
				<a  @click="goProduct(item.id)" v-for="(item,index) in listB" :key="index"  class="sglist-item bd-radius-10">
					 
						<img class="sglist-img " :src="item.imgurl+'.small.jpg'" />
						<div class="sglist-item-pd">
							<div class="sglist-title">{{item.title}}</div>
							<div class="flex mgb-5">
								<div class="cl-money">￥</div>
								<div class="cl-money f16">{{item.price}}</div>
								<div class="flex-1"></div>
								<div class="cl2">月销{{item.month_buy_num}}件</div>
							</div>
							
							 
						</div>
					 
				</a>
			</div>
		</div>
		
	`
})