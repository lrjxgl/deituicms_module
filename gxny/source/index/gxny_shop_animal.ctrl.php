<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gxny_shop_animalControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=gxny_shop_animal&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_gxny_shop_animal")->select($option,$rscount);
			if(!empty($list)){
				foreach($list as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gxny_shop_animal/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=gxny_shop_animal&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_shop_animal")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gxny_shop_animal/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_gxny_shop_animal")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gxny_shop_animal/show.html");
		}
		
		
		public function onBuy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid,"userid,money");
			$id=get_post("id","i");
			$pro=M("mod_gxny_shop_animal")->selectRow("id=".$id);
			 
			$money=$pro["price"];
			if($user["money"]<$money){
				$this->goAll("余额不足，请先充值",22);
			}
			M("mod_gxny_animal_order")->begin();
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"购买动物#".$pro["title"]."花了".$money."元"
			));
			$orderid=M("mod_gxny_animal_order")->insert(array(
				"userid"=>$userid,
				"shopid"=>$pro["shopid"],
				"animalid"=>$id,
				"createtime"=>date("Y-m-d H:i:s"),
				"money"=>$money,
				"back_money"=>$pro["back_money"],
				 
			));
			
			M("mod_gxny_animal_order")->commit();
			
			$this->goAll("下单成功");
		}
		
	}

?>