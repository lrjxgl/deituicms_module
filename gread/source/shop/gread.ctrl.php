<?php
	class greadControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			 
			$shop=MM("gread","gread_shop")->get(SHOPID);
			$neworder=M("mod_gread_order")->selectOne(array(
				"where"=>" shopid=".SHOPID." AND status=0 ",
				"fields"=>"count(*)"
			));
			$day=date("Y-m-d");
			$dayorder=M("mod_gread_order")->selectOne(array(
				"where"=>" shopid=".SHOPID." AND status=0 AND createtime like '".$day."%' ",
				"fields"=>"count(*)"
			));
			$dayorder=intval($dayorder);
			$daymoney=M("mod_gread_shop_money_log")->selectOne(array(
				"where"=>" shopid=".SHOPID."  AND createtime like '".$day."%' AND money>0",
				"fields"=>"sum(money) as ct"
			));
			$daymoney=intval($daymoney); 
			$alluser=M("mod_gread_user")->selectOne(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"count(*)"
			));
			$this->smarty->goAssign(array(
				"shop"=>$shop,
				"neworder"=>$neworder,
				"dayorder"=>$dayorder,
				"daymoney"=>$daymoney,
				"alluser"=>$alluser
			));
			$this->smarty->display("gread/index.html");
		}
		
		public function onMenu(){
			
			$this->smarty->display("menu.html");
		}
		
		public function onNotice(){
			$neworder=M("mod_gread_order")->selectRow("status=0 AND shopid=".SHOPID." ");
			$neworder2=M("mod_gread_backorder")->selectRow("status=0 AND shopid=".SHOPID." ");
			if($neworder||$neworder2){
				$neworder=1;
			}
			$ctime=date("Y-m-d H:i:s",time()-36000);
			$newMsg=M("mod_gread_guest")->selectRow("status=1 AND shopid=".SHOPID." AND ukey='shop' AND createtime>'".$ctime."' ");
			$data=array(
				"neworder"=>$neworder?1:0,
				"newMsg"=>$newMsg?1:0
			);
			echo json_encode($data);
		}
		
	}
?>