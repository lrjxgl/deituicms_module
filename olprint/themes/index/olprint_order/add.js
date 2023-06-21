var App=new Vue({
	el:"#App",
	data:function(){
		return {
			ptype:1,
			optype:{},
			page_num:1,
			print_num:1,
			fileurl:"",
			sendtype:0,
			money:10,
			bookid:bookid,
			book:{},
			send_money:3,
			ptypeList:[],
			 
		}
	},
	created:function(){
		this.getPage();
		
	},
	watch:{
		page_num:function(n,o){
			console.log(n,o)
			this.statMoney();
		},
		print_num:function(n,o){
			 
			this.statMoney();
		},
		sendtype:function(n,o){
			this.statMoney();
		},
		ptype:function(n,o){
			this.optype=this.ptypeList[n]
			this.statMoney();
		}
	},
	methods:{
		statMoney:function(){
			console.log(this.print_num)
			if(bookid>0){
				var money=parseInt(this.book.money*100)*this.print_num/100;
			}else{
				var money=this.print_num *((this.page_num-1) *  parseInt(this.optype.page_money*100) + parseInt(this.optype.start_money*100))/100;			
			}
			if(this.sendtype==1){
				 money+=parseFloat(this.send_money);
			}
			this.money=money;
		},
		getPage:function(){
			var that=this;
			$.ajax({
				url:"/module.php?m=olprint_order&a=add&ajax=1",
				dataType:"json",
				data:{
					bookid:bookid
				},
				success:function(res){
					that.ptype=res.data.ptype;
					that.optype=res.data.optype;
					that.ptypeList=res.data.ptypeList;
					that.book=res.data.book;
					 
					that.send_money=res.data.send_money;
					that.statMoney();
				}
			})
		},
		submit:function(){
			var that=this;
			if(!that.bookid && this.fileurl=='' && $("#imgsdata").val()=='' ){
				skyToast("请上传文件");
				return false;
			}
			if(!that.bookid && (this.page_num==0 || this.print_num==0)){
				skyToast("打印页数不能少于1页");
				return false;
			}
			$.ajax({
				url:"/module.php?m=olprint_order&a=order&ajax=1",
				type:"POST",
				dataType:"json",
				data:$("#eForm").serialize(),
				success:function(res){
					skyToast(res.message);
					if(res.error){
						return false;
					}
					 
					if(res.data.action=='finish'){
						window.location="/module.php?m=olprint_order&a=success";
					}else{
						window.location=res.data.payurl;
					} 
				}
			})
		}
	}
})

	function skyUpload(upid,url,success,error,uploadProgress)
		{
				 var vFD = new FormData();
				 var f= document.getElementById(upid).files;
				 $("#"+upid+"loading").show();
				 for(var i=0;i<f.length;i++){ 
				vFD.append('upimg', document.getElementById(upid).files[i]);
				// create XMLHttpRequest object, adding few event listeners, and POSTing our data
				var oXHR = new XMLHttpRequest();        
				oXHR.addEventListener('load', success, false);
				oXHR.addEventListener('error', error, false);
				if(uploadProgress!=undefined){
					oXHR.upload.addEventListener("progress", uploadProgress, false);
				}
				oXHR.open('POST',url);
				oXHR.send(vFD);
			
				 }
		}
			$(document).on("click",".js-upfile",function(){
				$("#upfile").click();
			})
			$(document).on("change","#upfile",function(){
				$("#upfile-loading").html("上传中...").show();
				skyUpload("upfile","/index.php?m=upload&a=UploadFile&ajax=1",function(e){
					
					var res = JSON.parse(e.target.responseText);
					if(res.error==0){
						$("#upfile-loading").html("上传成功...");
						//$("#fileurl").val(res.imgurl);
						 
						App.$data.fileurl=res.imgurl;
					}else{
						$("#upfile-loading").html("上传失败...");
					}
					
				},function(e){
					console.log(e)
				},function(e){
					console.log(e)
				})
			})