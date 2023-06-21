$(function(){
	
	$(document).on("click",".js-cart-add",function(){
		var bookid=$(this).attr("bookid");
		var $this=$(this);
		$.get("/moduleshop.php?m=gread_shop_incart&a=add&ajax=1",{
			bookid:bookid
		},function(data){
			skyToast(data.message);
			if(!data.error){
				$("#amount-"+bookid).val(data.data.num);
				$this.parents(".cart").find(".numbox").removeClass("none");
				$this.parents(".cart").addClass("cart-active");
			}
		},"json")
	})
	
	$(document).on("click",".js-cart-minus",function(){
		var bookid=$(this).attr("bookid");
		var $this=$(this);
		$.get("/moduleshop.php?m=gread_shop_incart&a=minus&ajax=1",{
			bookid:bookid
		},function(data){
			skyToast(data.message);
			if(!data.error){
				if(data.data.num==0){
					$this.parents(".cart").removeClass("cart-active");
					$this.parents(".numbox").addClass("none");
				}else{
					$("#amount-"+bookid).val(data.data.num);
				}
				
			}
		},"json")
	})
	
	$(document).on("click","#js-cart-buy",function(){
		$.get("/moduleshop.php?m=gread_shop_inorder&a=buy&ajax=1",function(data){
			skyToast(data.message);
		},"json")
	})
	
})
