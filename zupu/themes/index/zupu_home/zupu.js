 
Vue.component("page-zupu",{
	props:{
		gid:0
	}, 
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
	},
	template:`
		<div>
			<div class="input-flex">
				<input v-model="nickname" type="text" class="input-flex-text" />
				<div @click="search" class="input-flex-btn">搜</div>
			</div>
			
			<div class="flex-center pd-10" v-if="parent">
				<div class="pointer" @click="setParent(parent.id)">{{parent.nickname}}</div>
			</div>
			<div class="tabs-border mgb-10" v-if="us.length>0">
				<div :class="item.id==me.id?'tabs-border-active':''" class="tabs-border-item" @click="setParent(item.id)" v-for="(item,index) in us" :key="index">{{item.nickname}}</div>
			</div>
			<div v-if="child.length>0" class="row-box">
				<div class="bd-mp-10 pointer" @click="setParent(item.id)" v-for="(item,index) in child" :key="index">{{item.nickname}}</div>
			</div>
		</div>
	`
})