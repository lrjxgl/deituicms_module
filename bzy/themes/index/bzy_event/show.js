
App=new Vue({
	el:"#app",
	data:function(){
		return {
			tab:"home",
			grade:0,
			event:{},
			rankList:[],
			rankType:"day",
			has_num:0,
			inPlay:false,
			timer:0,
		}
	},
	created:function(){
		var that=this;
		$.ajax({
			url:"/module.php?m=bzy_event&a=show&ajax=1",
			dataType:"json",
			data:{
				eventid:eventid
			},
			success:function(res){
				that.event=res.data.event;
				if(Object.keys(res.data.join).length>0){
					that.grade=res.data.join.grade;
					that.has_num=res.data.join.max_num-res.data.join.use_num;
				}
				
			}
		}) 
	},
	methods:{
		setTab:function(tab){
			this.tab=tab;
			if(tab=='paihang'){
				this.getRankList();
			}
		},
		getPage:function(){
			
		},
		 
		setRank:function(rankType){
			this.rankType=rankType;
			this.getRankList();
		},
		getRankList:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=bzy_rank&ajax=1",
				dataType:"json",
				data:{
					eventid:eventid,
					rankType:this.rankType
				},
				success:function(res){
					that.rankList=res.data.list;
					
				}
			})
		},
		play:function(){
			var that=this;
			if(this.inPlay) return false;
			that.inPlay=true;
			clearTimeout(that.timer);
			that.timer=setTimeout(function(){
				that.inPlay=false;
			},3000)
			var container = document.getElementById('dicebox');
			$('.redpacket').remove();
			//var arr = this.randomFun();
			
			$.ajax({
				url:"/module.php?m=bzy_event&a=play&ajax=1",
				data:{
					eventid:eventid
				},
				dataType:"json",
				success:function(res){
					
					if(res.error){
						skyJs.alert({
							title:"出错啦",
							content:res.message
						})
						return false;
					}
					 
					var arr=res.rands;
					for (var i = 0 ; i<6;i++) {
						container.appendChild(that.createDice(arr[i]+1,i));
					}
					document.getElementById("tzmp3").play();
					that.has_num=res.has_num;
					setTimeout(function(){
						that.inPlay=false;
						that.grade=res.grade;
						skyJs.alert({
							title:"博饼提示",
							content:res.message
						})
					},1700)
					
				}
			})
			
		}, 
		createDice:function(num,i){
			var image = document.createElement('img');
			  	image.setAttribute("class","redpacket");
			  	image.id = "redpacket" + i;
			  	image.src = '/module/bzy/themes/index/img/' + (num-1) +'.jpg';
			  	return image;
		},
	}
})