
Vue.component("open-data",{
	props:{
		
	},
	data:function (){
		return {
			tablename:"",
			list:[],
			modList:[
				{
					"tablename":"ershou_product",
					"title":"二手商城"
				}
			]
		}
	},
	created:function(){
		
	},
	methods:{
		getPage:function(){
			
		},
		setTable:function(tablename){
			this.tablename=tablename;
			this.getList();
		},
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=group_open_data&a=list&ajax=1",
				dataType:"json",
				data:{
					tablename:this.tablename,
				},
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		insertProduct:function(item){
			 
			this.$emit("call-parent",{
				tablename:this.tablename,
				id:item.productid,
				title:item.description
			})
		},
		close:function(){
			this.$emit("close")
		}
	},
	template:`
	<div>
		<div @click="close" class="modal-mask"></div>
		<div class="modal">
			<div class="row-box">
			
				<div class="flex">
					<div class="mgr-10">
						<div @click="setTable(item.tablename)" v-for="(item,index) in modList" :key="index">
							{{item.title}}
						</div>
					</div>
					<div class="flex-1">
						<div>
							<div class="bd-mp-10" @click="insertProduct(item)" v-for="(item,index) in list" :key="index">
								<div>{{item.description}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	`
})

var mdApp=new Vue({
	el:"#comApp",
	data:function(){
		return {
			aa:"xx",
			open_data:"",
			open_data_title:"请选择",
			openModal:false
		}
	},
	methods:{
		setProduct:function(e){
			this.open_data=e.tablename+":"+e.id;
			this.open_data_title=e.title;
			/*console.log(e);
			$("#open_data").val(e.tablename+":"+e.id)
			$("#open_data-title").html(e.title)
			*/
			this.openModal=false;
		},
		close:function(){
			this.openModal=false;
		}
	}
})