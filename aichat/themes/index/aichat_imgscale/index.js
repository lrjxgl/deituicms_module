var App=new Vue({
	el:"#App",
	data:function(){
		return {
			oimg:"",
			true_oimg:"",
			prompt:"",
			negative_prompt:"",
			imgurl:"",
			timer:0,
			newid:0,
			inpost:false
		}
	},
	methods:{
		upClick:function(){
			$("#upimg").click();
		},
		uploadImg:function(e){
			var that=this;
			that.imgurl='';
			var src, url = window.URL || window.webkitURL || window.mozURL;
			var	file = e.target.files[0];
			var vFD = new FormData();
			vFD.append('upimg', file);
			// create XMLHttpRequest object, adding few event listeners, and POSTing our data
			var oXHR = new XMLHttpRequest();        
			oXHR.addEventListener('load', function(e){
				var res=eval("("+e.target.responseText+")");
				if(res.error){
					return false;
				}
				that.true_oimg=res.data.trueimgurl;
				that.oimg=res.data.imgurl;
			}, false);
			oXHR.addEventListener('error', function(e){
				
			}, false);
			 
			oXHR.open('POST',"/index.php?m=upload&a=img");
			oXHR.send(vFD);
		},
		submit:function(e){
			var that=this;
			if(that.inpost){
				return false;
			}
			that.inpost=true;
			that.imgurl='';
			$.ajax({
				url:"/module.php?m=aichat_imgscale&a=save&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					oimg:that.oimg,
					prompt:that.prompt,
					negative_prompt:that.negative_prompt
				},
				success:function(res){
					skyToast(res.message)
					that.newid=res.data.id;
					clearInterval(that.timer)
					that.timer=setInterval(function(){
						that.getImg();
					},1000)
				}
			})
		},
		getImg:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=aichat_imgscale&a=get&ajax=1",
				dataType:"json",
				type:"POST",
				data:{
					id:that.newid
				},
				success:function(res){
					if(res.data.create_status==1){
						that.imgurl=res.data.imgurl;
						clearInterval(that.timer)
						that.inpost=false;
						 
					} 
					
				}
			})
		}
	}
})