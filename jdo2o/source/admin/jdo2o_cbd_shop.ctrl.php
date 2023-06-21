<?php
class jdo2o_cbd_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$cbdid=get('cbdid','i');
		$sql=" select sq.id,s.shopid,s.shopname,s.address,s.telephone from ".table('mod_jdo2o_cbd_shop')." as sq 
			   left join ".table('mod_jdo2o_shop')." as s
			   on sq.shopid=s.shopid
			   where sq.cbdid=".$cbdid
		;
		 
		$shoplist=M("mod_jdo2o_cbd_shop")->getAll($sql);
		$cbd=M("mod_jdo2o_cbd")->selectRow("cbdid=".$cbdid);
		$this->smarty->goAssign(array(
			"shoplist"=>$shoplist,
			"cbd"=>$cbd
		));
		$this->smarty->display("jdo2o_cbd_shop/index.html");
	}
	
	public function onList(){
		$cbdid=get('cbdid','i');
		$sql=" select sq.id,s.shopid,s.shopname,s.address,s.telephone from ".table('mod_jdo2o_cbd_shop')." as sq 
			   left join ".table('mod_jdo2o_shop')." as s
			   on sq.shopid=s.shopid
			   where sq.cbdid=".$cbdid
		; 
		$keyword=get("keyword","h");
		if($keyword){
			$sql.=" AND s.shopname like '".$keyword."%' ";
		}
		$shoplist=M("mod_jdo2o_cbd_shop")->getAll($sql);
		$cbd=M("mod_jdo2o_cbd")->selectRow("cbdid=".$cbdid);
		$this->smarty->goAssign(array(
			"shoplist"=>$shoplist,
			"cbd"=>$cbd
		));
	}
	
	public function onToggleAdd(){
		$cbdid=get('cbdid','i');
		$shopid=get("shopid","i");
		$row=M("mod_jdo2o_cbd_shop")->selectRow("cbdid=".$cbdid." AND shopid=".$shopid);
		$status=0;
		if($row){
			M("mod_jdo2o_cbd_shop")->delete("cbdid=".$cbdid." AND shopid=".$shopid);
		}else{
			M("mod_jdo2o_cbd_shop")->insert(array(
				"cbdid"=>$cbdid,
				"shopid"=>$shopid
			));
			$status=1;
		}
		$this->goAll("操作成功",0,$status);
		
	}
	
	public function onShop(){
		$cbdid=get('cbdid','i');
		$where=" status=1 ";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND shopname like '".$keyword."%' ";
		}
		$list=M("mod_jdo2o_shop")->select(array(
			"where"=>$where,
			"fields"=>" shopid,shopname,address,telephone"
		));
		
		$shopids=M("mod_jdo2o_cbd_shop")->selectCols(array(
			"where"=>" cbdid=".$cbdid,
			"fields"=>" shopid"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["status"]=0;
				if($shopids && in_array($v["shopid"],$shopids)){
					$v["status"]=1;
				}
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
	
}
?>