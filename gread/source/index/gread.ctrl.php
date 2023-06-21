<?php
	class greadControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			
			$shopid=MM("gread","gread")->getShopid();
			$shop=false;
			if($shopid){
				$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
			}
			 
			$fromapp=get("fromapp");
			switch($fromapp){
				case "uniapp":
					$flashList=M("ad")->listByNo("uniapp-gread-index");
					$adList=M("ad")->listByNo("uniapp-gread-ad");
					$navList=M("ad")->listByNo("uniapp-gread-nav"); 
					break;
				default:
					$flashList=M("ad")->listByNo("wap-gread-index");
					$adList=M("ad")->listByNo("wap-gread-ad");
					$navList=M("ad")->listByNo("wap-gread-nav"); 
					break;
			}
			$articleList=MM("gread","gread_article")->Dselect(array(
				"where"=>"status=1 AND isindex=1 ",
				"limit"=>3
			));
			$bookList=MM("gread","gread_book")->Dselect(array(
				"where"=>" status=1 ",
				"limit"=>6,
				"order"=>"bookid DESC"
			));
			$seo=M("seo")->get("gread","default");
			$this->smarty->goAssign(array(
				"articleList"=>$articleList,
				"bookList"=>$bookList,
				"flashList"=>$flashList,
				"adList"=>$adList,
				"navList"=>$navList,
				"shopid"=>$shopid,
				"shop"=>$shop,
				 
				"seo"=>$seo
			));
			$this->smarty->display("gread/index.html");
		}
		
		public function onHome(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$guser=MM("gread","gread_user")->get($userid);
			$user=M("user")->getUser($userid,"userid,money,user_head,nickname");
			$this->smarty->goassign(array(
				"guser"=>$guser,
				"user"=>$user
			));
			$this->smarty->display("gread/home.html");
		}
		public function onborrow(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			//判断是否有会员卡
			$guser=MM("gread","gread_user")->get($userid);
					 
			$ucard=M("mod_gread_user_card")->selectRow("userid=".$userid);
			if(!$ucard || strtotime($guser['endtime'])<time()){
				header("Location: /module.php?m=gread_user_card");
				$this->sexit();
			}
			//未选择商店 折跳转到商店列表
			if(!$guser['shopid']){
				header("Location: /module.php?m=gread_shop&a=near");
				$this->sexit();
			}	
			header("Location: /module.php?m=gread_shop&shopid=".$guser['shopid']);
			$this->sexit();
		}
		
	}
?>