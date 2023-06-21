App=new Vue({
	el:"#App",
	data:function(){
		return {
			date:"",
			word:"",
			dayRes:{}
		}
	},
	created:function(){
		var d=new Date();
		var year=d.getFullYear();
		var month=d.getMonth()+1;
		month=parseInt(month)<10?'0'+month:month+"";
		var day=d.getDate();
		day=parseInt(day)<10?'0'+day:day+"";
		this.date=year+"-"+month+"-"+day;
		this.search();
		var year = SolarYear.fromDate(new Date());
		 
		generate(year,d.getMonth());
	},
	methods:{
		setDate:function(date){
			this.date=date;
			this.search();
		},
		search:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=huangli&a=api&ajax=1",
				dataType:"json",
				data:{
					date:this.date,
					word:this.word
				},
				success:function(res){
					that.dayRes=res.data;
					console.log(that.dayRes)
				}
			})
		}
	}
})