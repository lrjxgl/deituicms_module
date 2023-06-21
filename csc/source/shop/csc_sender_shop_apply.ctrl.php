<?php
class csc_sender_shop_applyControl extends skymvc{
		
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status=0 AND shopid=".SHOPID;
		$url="/moduleshop.php?m=csc_sender_shop_apply&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_csc_sender_shop_apply")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$sids[]=$v["senderid"];
			}
			$senders=MM("csc","csc_sender")->getListByIds($sids);
			foreach($data as $k=>$v){
				$status=$v["status"];
				$v=$senders[$v["senderid"]];
				$v["status"]=$status;
				$data[$k]=$v;
			}
		}
		
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$data,
			"pagelist"=>$pagelist,
			"per_page"=>$per_page,
			"rscount"=>$rscount
		));
		$this->smarty->display("csc_sender_shop_apply/index.html");
	}
	
	public function onAccept(){
		$senderid=get("senderid","i");
		$row=M("mod_csc_sender_shop_apply")->selectRow("senderid=".$senderid." AND status=0 AND shopid=".SHOPID);
		$sender=M("mod_csc_sender")->selectRow("senderid=".$senderid);
		if($sender["shopid"]){
			$this->goAll("用户已经绑定商家了",1);
		}
		if(!$row|| $row["status"]!=0){
			$this->goAll("无法处理",1);
		}
		M("mod_csc_sender_shop_apply")->update(array(
			"status"=>1
		),"id=".$row["id"]);
		M("mod_csc_sender")->update(array(
			"shopid"=>SHOPID
		),"senderid=".$senderid);
		$this->goAll("绑定成功");
	}
	
	public function onForbid(){
		$senderid=get("senderid","i");
		M("mod_csc_sender_shop_apply")->update(array(
			"status"=>4
		),"senderid=".$senderid." AND shopid=".SHOPID);
		$this->goAll("拒绝成功");
	}
	
}
