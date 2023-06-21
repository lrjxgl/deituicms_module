<?php
	class mscard_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onInit(){
			$s=M("mod_mscard_shop")->selectRow("shopid=".SHOPID);
			if(!$s){
				$shop=M("shop")->selectRow("shopid=".SHOPID);
				M("mod_mscard_shop")->insert(array(
					"shopid"=>$shop['shopid'],
					"shopname"=>$shop['shopname'],
					"logo"=>$shop['logo'],
					"lat"=>$shop['lat'],
					"lng"=>$shop['lng'],
				));
			}
		}
		
		public function onDefault(){
			$shop=M("mod_mscard_shop")->selectRow("shopid=".SHOPID);
			$this->smarty->goAssign(array(
				"shop"=>$shop
			));
			$this->smarty->display("mscard_shop/index.html");
		}
		
	}
?>