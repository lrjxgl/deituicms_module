Vue.component("page-chat",{
	props:{
		gid:0
	},
	data:function(){
		return {
			list:[],
			content:""
		}
	},
	created:function(){
		this.getPage();
	},
	methods:{
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=zupu_chat_msg&a=home&ajax=1",
				dataType:"json",
				data:{
					gid:this.gid
				},
				success:function(res){
					that.list=res.data.list;
					that.$nextTick(function(){
						$(window).scrollTop(10000)
					})
				}
			})
		},
		save:function(){
			var that=this;
			if(that.content==''){
				return false;
			}
			$.ajax({
				url:"/module.php?m=zupu_chat_msg&a=save&ajax=1",
				dataType:"json",
				data:{
					gid:this.gid,
					content:this.content
				},
				type:"POST",
				success:function(res){
					skyToast(res.message)
					that.content="";
					that.getPage();
				}
			})
		}
	},
	template:`
		<div>
			<div class="main-body">
			    <div class="msgbox">
			    	
			    	<div class="msglist">
			    	<div class="msgnote none" id="msgnote">你有新消息了</div>
			    	<div class="">
			    	    <div class="scroll-div-y">
			    	        <div class="flexlist" id="msglist">
								<div class="flexlist-item mgb-10" v-for="(item,index) in list" :key="index">
									<img class="wh-60 bd-radius-50 mgr-5" :src="item.user_head+'.100x100.jpg'" />
									<div class="flex-1">
										<div class="flex mgb-5">
											<div class="cl2 mgr-5">{{item.nickname}}</div>
											<div class="cl3">{{item.createtime}}</div>
										</div>
										
										<div>{{item.content}}</div>
									</div>
									
								</div>
							
							</div>
			    	    </div>
			    	</div>
			    	</div>
					<div style="height:100px;"></div> 
					<div class="footer" style="bottom:50px;">
						<div id="scrollEnd" style="height: 0px;"></div> 
						
						<div class="input-flex flex-1">
							<input class="input-flex-text" type="text" v-model="content" />
					 		 
							<div @click="save()" class="btn">发送</div>
						</div>
					</div>
					
				</div>
			
				<div style="display:none ">
					<input type="file" id="up-img-f" >
				
				</div>
			</div>
		</div>
	`
	 
})