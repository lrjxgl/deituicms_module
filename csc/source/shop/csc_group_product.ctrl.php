<?php
class csc_group_productControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("csc_group_product/index.html");
	}
	public function onTaglist(){
		$list=M("mod_csc_group")->select(array(
			"where"=>" status=1"
		));
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list
			)
		));
	}
	public function onProduct(){
		$where=" shopid=".SHOPID;
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' ";
		}
		$list=MM("csc","csc_product")->select(array(
			"where"=>$where
		));
		//选中的
		$gid=get("gid","i");
		$ids=false;
		if($gid){
			$ids=M("mod_csc_group_product")->selectCols(array(
				"where"=>" gid=".$gid." AND shopid=".SHOPID,
				"fields"=>"productid"
			));
		}
		if($list){
			foreach($list as $k=>$v){
				$v["ingroup"]=0;
				if(!empty($ids) && in_array($v["id"],$ids) ){
					$v["ingroup"]=1;
				}
				$list[$k]=$v;
			}
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list
			)
		));
	}
	
	public function onToggle(){
		$gid=get("gid","i");
		$productid=get("productid","i");
		$row=M("mod_csc_group_product")->selectRow("shopid=".SHOPID." AND gid=".$gid." AND productid=".$productid);
		if($row){
			M("mod_csc_group_product")->delete("gpid=".$row["gpid"]);
			$action="delete";
		}else{
			M("mod_csc_group_product")->insert(array(
				"gid"=>$gid,
				"productid"=>$productid,
				"shopid"=>SHOPID
			));
			$action="insert";
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"action"=>$action
			)
		));
		
	}
}