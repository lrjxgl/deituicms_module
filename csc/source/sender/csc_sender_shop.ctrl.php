<?php
class csc_sender_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=MM("csc","csc_sender")->get(SENDERID);
		$shop=MM("csc","csc_shop")->get($sender["shopid"]);
		$shopList=MM("csc","csc_shop")->select(array(
			"where"=>" status=1 ",
			"fields"=>"shopid,shopname"
		));
		$apply=M("mod_csc_sender_shop_apply")->selectRow("senderid=".SENDERID." AND status=0 ");
		if($apply){
			$apply["shopname"]=M("mod_csc_shop")->selectOne(array(
				"where"=>"shopid=".$apply["shopid"],
				"fields"=>"shopname"
			));
		}
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			"shop"=>$shop,
			"shopList"=>$shopList,
			"apply"=>$apply
		));
		$this->smarty->display("csc_sender_shop/index.html");
	}
	
	public function onCancel(){
		MM("csc","csc_sender")->update(array(
			"shopid"=>0
		),"senderid=".SENDERID);
		$this->goAll("解绑成功");
	}
	
	public function onApply(){
		$shopid=get("shopid","i");
		$sender=MM("csc","csc_sender")->get(SENDERID);
		if($sender["shopid"]){
			$this->goAll("请先解绑商家",1);
		}
		$apply=M("mod_csc_sender_shop_apply")->selectRow("senderid=".SENDERID." AND status=0 ");
		if($apply){
			$this->goAll("已经申请，请等待审核",1);
		}
		M("mod_csc_sender_shop_apply")->insert(array(
			"shopid"=>$shopid,
			"senderid"=>SENDERID,
			"dateline"=>time()
		));
		$this->goAll("申请成功，请等待商家确认");
	}
	
	
}
?>