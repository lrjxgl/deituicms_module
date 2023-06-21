<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class vipcard_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=vipcard_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_vipcard_apply")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("vipcard_apply/index.html");
		}
		
		public function onPass(){
			$id=get("id","i");
			$row=M("mod_vipcard_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("该申请已处理了",1);
			}
			M("mod_vipcard_apply")->update(array(
				"status"=>1
			),"id=".$id);
			$card=M("mod_vipcard")->selectRow("userid=".$row["userid"]);
			if($card){
				$this->goAll("该用户已经开通vip了",1);
			}
			M("mod_vipcard")->insert(array(
				"userid"=>$row["userid"],
				"telephone"=>$row["telephone"],
				"nickname"=>$row["nickname"],
				"idcard"=>$row["idcard"],
				"status"=>1
			));
			$this->goAll("审核通过成功");
			
		}
		public function onForbid(){
			$id=get("id","i");
			$row=M("mod_vipcard_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("该申请已处理了",1);
			}
			M("mod_vipcard_apply")->update(array(
				"status"=>2
			),"id=".$id);
			$this->goAll("审核禁止成功");
		}
		
	}

?>