<?php
class gxny_userControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,gold,grade,money,follow_num,followed_num,gender");
		 
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			 
			 
		));
		$this->smarty->display("gxny_user/index.html");
	}
	
	public function onHome(){
		$userid=get("userid","i");
		$this->smarty->goAssign(array(
			"userid"=>$userid
		));
		$this->smarty->display("gxny_user/home.html");
	}
	public function onApi(){
		$userid=get("userid","i");
		$user=M("user")->getUser($userid,"userid,nickname,user_head,gender,follow_num,followed_num,description");
		 
		$ssuserid=M("login")->userid;
		$follow=M("follow")->selectRow("userid=".$ssuserid." AND t_userid=".$userid);
		if($follow){
			$user["isFollow"]=1;
		}else{
			$user["isFollow"]=0;
		}
		$proList=MM("gxny","gxny_shop_product")->select(array(
			"where"=>" userid=".$userid."  ",
			"order"=>"id ASC"
		));
		
		$this->smarty->goAssign(array(
			"user"=>$user,
			
			"proList"=>$proList
			
		));
	}
	
	public function onBlog(){
		$userid=get("userid","i");
		$start=get("per_page","i");
		$limit=6;
		$order=" id DESC";
		$where=" userid=".$userid." AND status in(0,1) ";
		$productid=get("productid","i");
		if($productid){
			$where.=" AND productid=".$productid;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("gxny","gxny_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list,
				"per_page"=>$per_page
			)
			
		));
	}
}