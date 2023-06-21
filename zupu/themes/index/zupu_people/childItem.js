Vue.component("childItem",{
	props:{
		list2:[]
	},
	data:function(){
		
	},
	created:function(){
		console.log(this.list2) 
	},
	methods:{
		getPage:function(){
			
		}
	},
	template:`
		
		<div v-for="(item,index) in list2" :key="index" class="">
			<div>
				{{item.nickname}}
			</div>
			<child-item  v-if="item.child && Object.keys(item.child).length>0"  :list="item.child"></child-item>
		</div>
	`
})