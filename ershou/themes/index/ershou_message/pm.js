 
Vue.component('page-pm',{
	 
		data: function() {
			return {
				list: [],
				t_userid: 0,
				isFirst: true,
				per_page: 0,
				unLogin: true
			}
		},
		created: function(ops) {
			 
			this.getPage();
		},
		onShow: function() {
			this.getPage();
		},
		onReachBottom: function() {
			this.getList();
		},
		methods: {
			goPm: function(userid) {
				window.location="/index.php?m=pm&a=detail&userid="+userid

			},
			getPage: function() {
				var that = this;
				$.ajax({
					dataType:"json",
					url: "/index.php?m=pm&ajax=1",
					unLogin: true,
					success: function(res) {
						if (res.error == 1000) {
							return false;
						}
						that.unLogin = false;
						that.list = res.data.msglist;
					}
				})
			},
			getList: function() {
				var that = this;
				if (that.per_page == 0 && !that.isFirst) {
					return false;
				}
				$.ajax({
					dataType:"json",
					url: "/index.php?m=pm&ajax=1",
					data: {
						per_page: that.per_page
					},
					success: function(res) {
						that.per_page = res.data.per_page;
						if (that.isFirst) {
							for (var i in res.data.msglist) {
								that.list.push(res.data.msglist[i]);
							}
							that.isFirst = false;
						} else {
							that.list = res.data.msglist;
						}


					}
				})
			}

		},
		template:`
			<div >
				<div class="emptyData" v-if="Object.keys(list).length==0">暂无消息</div>
				<div v-else class="pmList">
					<div @click="goPm(item.t_userid)" class="row-box mgb-5" v-for="(item,index) in list" :key="index">
						<div class="flex flex-ai-center" v-if="item.isme">
							<img class="pm-head mgr-5" :src="item.t_user_head+'.100x100.jpg'" />
							<div class="flex-1">
								<div class="flex mgb-5">
									<text v-if="item.status==0" class="badge badge-mini"></text>
									<div class="mgr-5 f12 cl3 mgr-10 ">{{item.t_nickname}} </div>
			
									<div class="f12 cl3">{{item.timeago}}</div>
									<div class="flex-1"></div>
									<div class="mgl-5 f12 cl3">我</div>
								</div>
								<div class="pm-r-msg">
									{{item.content}}
								</div>
			
							</div>
							<img class="pm-head mgl-5" :src="item.user_head+'.100x100.jpg'" />
						</div>
						<div class="flex flex-ai-center" v-else>
							<img class="pm-head mgr-5" :src="item.t_user_head+'.100x100.jpg'" />
							<div class="flex-1">
								<div class="flex">
									<text v-if="item.status==0" class="badge badge-mini"></text>
									<div class="mgb-5 f12 cl3">{{item.t_nickname}}</div>
									<div class="flex-1"></div>
									<div class="f12 cl3">{{item.timeago}}</div>
			
								</div>
			
								<div class="pm-l-msg">
									{{item.content}}
								</div>
							</div>
			
			
						</div>
					</div>
				</div>
				<div @click="getList()" v-if="per_page>0" class="loadMore">加载更多</div>
			</div>
		`
	})
 