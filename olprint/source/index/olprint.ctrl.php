<?php
class olprintControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$list=M("mod_olprint_book")->select(array(
			"where"=>" status=1 ",
			"limit"=>12
		));
		if(SHOPID==0){
			if(get("ajax")){
				$this->goAll("请选择打印店",1,0,"/module.php?m=olprint_shoplist");
			}else{
				header("Location: /module.php?m=olprint_shoplist");
				exit;
			}
		}
		$shop=MM("olprint","olprint_shop")->get(SHOPID,"shopid,shopname,imgurl");
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"shop"=>$shop
		));
		$this->smarty->display("olprint/index.html");
	}
	
	public function onUser(){
		M("login")->checkLogin(true);
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,user_head,nickname,money,gold");
		$topic_num=M("mod_olprint_book")->selectOne(array(
			"where"=>" userid=".$userid,
			"fields"=>" count(*)  as ct"
		));
		$this->smarty->goAssign(array(
			"user"=>$user,
			"topic_num"=>$topic_num
		));
		$this->smarty->display("olprint/user.html");
	}
	
}