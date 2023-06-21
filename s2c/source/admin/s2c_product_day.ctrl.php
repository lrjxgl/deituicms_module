<?php
	class s2c_product_dayControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			switch(get("day")){
				case -1:
					$sendtime=date("Ymd",time()-3600*24);
					break;
				case 2:
					$sendtime=date("Ymd",time()+3600*24);
					break;
				default:
					$sendtime=date("Ymd");
					break;
			}
			
			$sql="select op.productid,op.ksid,sum(op.amount) as amount 
				from ".table("mod_s2c_order")." as o 
				left join ".table("mod_s2c_order_product")." as op
				on o.orderid=op.orderid 
				where o.ispay=1 AND o.status<4 AND o.sendtime=".$sendtime."
				group by op.productid,op.ksid
			";
			$list=M("mod_s2c_order")->getAll($sql);
			if($list){
				foreach($list as $v){
					$proids[]=$v["productid"];
					$ksids[]=$v["ksid"];
				}
				$pros=MM("s2c","s2c_product")->getListByIds($proids);
				$kslist=MM("s2c","s2c_product_ks")->getListByIds($ksids);
				foreach($list as $k=>$v){
					$v["title"]=$pros[$v["productid"]]["title"];
					$v["ks_title"]=$kslist[$v["ksid"]]["title"]."_".$kslist[$v["ksid"]]["size"];
					$list[$k]=$v;
				}
			}
			$this->smarty->goAssign(array(
				"list"=>$list,
				 
			));
			$this->smarty->display("s2c_product_day/index.html");
		}
		
	}
?>