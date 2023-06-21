<?php
class csc_paotui_lmshop_cartControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$res=M("mod_csc_paotui_lmshop_cart")->select(array(
			"where"=>" userid=".$userid." AND shopid=".SHOPID,
			"fields"=>"productid,num"
		));
		if($res){
			foreach($res as $rs){
				$proids[]=$rs["productid"];
			}
			$pros=MM("csc","csc_paotui_lmshop_product")->getListByIds($proids);
			 
			foreach($res as $k=>$rs){
				$rs["title"]=$pros[$rs["productid"]]["title"];
				$res[$k]=$rs;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$res
		));
		$this->smarty->display("csc_paotui_lmshop_cart/index.html");
		
	}
	public function onAdd(){
		$userid=M("login")->userid;
		$productid=get("productid","i");
		$num=get("num","i");
		$row=M("mod_csc_paotui_lmshop_cart")->selectRow(array(
			"where"=>" userid=".$userid." AND shopid=".SHOPID." AND productid=".$productid
		));
		$pro=M("mod_csc_paotui_lmshop_product")->selectRow("productid=".$productid);
		if(empty($pro)){
			$this->goAll("error",1);
		}
		if($row){
			if($num==0){
				M("mod_csc_paotui_lmshop_cart")->delete("cartid=".$row["cartid"]);
			}else{
				M("mod_csc_paotui_lmshop_cart")->update(array(
					"num"=>$num,
					"dateline"=>time()
				),"cartid=".$row["cartid"]);
			}
		}else{
			M("mod_csc_paotui_lmshop_cart")->insert(array(
				"num"=>$num,
				"productid"=>$productid,
				"userid"=>$userid,
				"dateline"=>time(),
				"lmid"=>$pro["lmid"],
				"shopid"=>SHOPID
			));
		}
		$this->goAll("success",0,$num);
		
	}
	
}