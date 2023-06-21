<?php
	class flk_cartModel extends model{
		public $table="mod_flk_cart";
		public function __construct(){
			parent::__construct();
		}
		public function Dselect($option=array()){
			$res=$this->select($option);
			if($res){
				foreach($res as $rs){
					$productids[]=$rs["productid"];
					$ksids[]=$rs["ksid"];
				}
				$productids=array_unique($productids);
				$ksids=array_unique($ksids);
				$pros=MM("flk","flk_product")->getListByIds($productids);
				$kss=MM("flk","flk_product_ks")->getListByIds($ksids);
				foreach($res as $k=> $rs){
					$product=$pros[$rs["productid"]];
					$rs["price"]=$product["price"];
					$rs["title"]=$product["title"];
					$rs["imgurl"]=$product["imgurl"];
					$rs["weight"]=$product["weight"];
					$rs["total_num"]=$product["total_num"];
					$rs["ks_title"]="";
					if($product["lower_price"]>0){
						$rs["price"]=$product["lower_price"];
					}
					if($rs["ksid"]){
						$rs["total_num"]=$kss[$rs["ksid"]]["total_num"];
						$rs["ks_title"]=$kss[$rs["ksid"]]["title"].$kss[$rs["ksid"]]["size"];
						$rs["price"]=$kss[$rs["ksid"]]["price"];
					}
					$res[$k]=$rs;
				}
				return $res;
			}
		}
		
		public function getProductAmount($where){
			$list=MM("flk","flk_cart")->select(array("where"=>$where));
			if($list){
				foreach($list as $v){
					$data[$v['productid']]=$v['amount'];
				}
			}
			return $data;
		}
		
	}