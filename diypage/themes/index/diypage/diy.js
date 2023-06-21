var uiId=0;
		var p=$("#diy-ui");
		
		$(document).on("click","#diyPageBox a",function(event){
		 
			event.preventDefault();
			return false;
		})
		var drag = document.getElementById('diy-ui');
		var uiData=[];
		$(function(){
			var cmid=0;
			var ops = {
				animation: 1000,
				//拖动结束
				onEnd: function (evt) {
					var o=$(".diyui-com");
					var len=o.length;
					var nuiData=[];
					for(var i=0;i<len;i++){
						var id=o.eq(i).attr("id");
						for(var j=0;j<len;j++){
							if(uiData[j].id==id){
								nuiData.push(uiData[j]);
							}
						}
					}
					uiData=nuiData; 
				},};
			//初始化
			var sortable = Sortable.create(drag, ops);
			$(document).on("click",".js-ui",function(){
				var cm=$(this).attr("cm");
				var uiId=++cmid;
				uiData.push({
					"id":uiId,
					"name":cm,
					"api":"default",
					"order":uiData.length,
					"dataId":1
				});
				console.log(uiData);
				$.ajax({
					url:"/module.php?m=diypage&a=ui&ui="+cm,
					success:function(e){
						var html='<div id="'+uiId+'" class="diyui-com">'
						
						html+=`	<div class="diyui-com-tool">
								<div class="pointer mgr-5 js-com-del">删除</div>
								<div class="pointer js-com-set">编辑</div>
							</div>
							
						`;
						html+=e;
						html+=`</div>`;
						p.append(html)
					}
				})
				
			})
			
			$(document).on("click",".js-com-set",function(){
				uiId=$(this).parents(".diyui-com").attr("id");
				var api=$(this).attr("api");
				uiApp.$data.show=true;
				
			})
			
			$(document).on("click",".js-com-del",function(e){
				console.log("delete")
				var pp=$(this).parents(".diyui-com");
				skyJs.confirm({
					content:"确认删除吗",
					success:function(){
						pp.remove();
					}
				})
				 
			})
			
			$(document).on("click","#diyPage-save",function(){
				 
				$.ajax({
					url:"/module.php?m=diypage_userpage&a=save&ajax=1",
					type:"POST",
					data:{
						content:JSON.stringify(uiData)
					},
					success:function(res){
						skyJs.toast("保存成功")
					}
					
				})
			}) 
		})