Vue.component("ftp-host",{
	data:function(){
		return {
			tab:"list",
			list:[],
			ftp:{
				title:"",
				host:"",
				port:"",
				user:"",
				pass:""
			}
		}
	},
	created:function(){
		this.getList();
	},
	methods:{
		getList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ftp_host&ajax=1",
				dataType:"json",
				success:function(res){
					that.list=res.data.list;
				}
			})
		},
		save:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=ftp_host&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					title:this.ftp.title,
					host:this.ftp.host,
					port:this.ftp.port,
					user:this.ftp.user,
					pass:this.ftp.pass
				},
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					that.tab="list";
					that.ftp={} 
					that.getList()
					
				}
			})
		},
		setHost:function(item){
			localStorage.setItem("ftpid",item.ftpid);
			
			this.$emit("call-parent",item)
		}
	},
	template:`
	<div>
		<div class="tabs-border">
			<div @click="tab='list'" :class="tab=='list'?'tabs-border-active':''"  class="tabs-border-item">主机列表</div>
			<div @click="tab='add'" :class="tab=='add'?'tabs-border-active':''" class="tabs-border-item">新增主机</div>
		</div>
		<div class="row-box" v-if="tab=='list'">
			<div @click="setHost(item)" class="bd-mp-10" v-for="(item,index) in list" :key="index">{{item.title}}</div>
		</div>
		<div v-if="tab=='add'">
			<div class="input-flex">
				<div class="input-flex-label">名称</div>
				<input class="input-flex-text" v-model="ftp.title" />
			</div>
			<div class="input-flex">
				<div class="input-flex-label">Host</div>
				<input class="input-flex-text" v-model="ftp.host" />
			</div>
			<div class="input-flex">
				<div class="input-flex-label">Port</div>
				<input class="input-flex-text" v-model="ftp.port" />
			</div>
			<div class="input-flex">
				<div class="input-flex-label">User</div>
				<input class="input-flex-text" v-model="ftp.user" />
			</div>
			<div class="input-flex">
				<div class="input-flex-label">Pass</div>
				<input class="input-flex-text" v-model="ftp.pass" />
			</div>
			<div @click="save" class="btn-row-submit">保存</div>
		</div>
	</div>
	`
})