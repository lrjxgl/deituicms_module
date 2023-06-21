<?php
	class s2c_cartModel extends model{
		public $table="mod_s2c_cart";
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
				$pros=MM("s2c","s2c_product")->getListByIds($productids);
				$kss=MM("s2c","s2c_product_ks")->getListByIds($ksids);
				foreach($res as $k=> $rs){
					$product=$pros[$rs["productid"]];
					$rs["price"]=$product["price"];
					$rs["title"]=$product["title"];
					$rs["imgurl"]=$product["imgurl"];
					 
					$rs["ks_title"]="";
					if($rs["ksid"]){
						$rs["ks_title"]=$kss[$rs["ksid"]]["title"].$kss[$rs["ksid"]]["size"];
						$rs["price"]=$kss[$rs["ksid"]]["price"];
					}
					$res[$k]=$rs;
				}
				return $res;
			}
		}
		
		public function getProductAmount($where){
			
			$list=MM("s2c","s2c_cart")->select(array("where"=>$where));
			$data=[];
			if($list){
				foreach($list as $v){
					$data[$v['productid']]=$v['amount'];
				}
			}
			return $data;
		}
		
	}