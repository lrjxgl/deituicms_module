<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_sender_bankcardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND senderid=".SENDERID;
			$url="/moduleshop.php?m=csc_sender_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_sender_bankcard")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("csc_sender_bankcard/index.html");
		}
		
		public function onAdd(){

			$bankList=MM("csc","csc_bankcard")->bankList();
			 
			$sender=M("mod_csc_sender")->selectRow(array(
				"where"=>" senderid=".SENDERID,
				"fields"=>"senderid,telephone"
			));
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"bankList"=>$bankList,
				"sender"=>$sender
			));
			$this->smarty->display("csc_sender_bankcard/add.html");
		}
		 
		
		public function onSave(){
			$paypwd=post("paypwd");
			$pwd=M("mod_csc_sender_paypwd")->selectRow("senderid=".SENDERID); 
			if(!$pwd){
				$this->goAll("请先设置支付密码",1,"/sender.php?m=csc_sender_bankcard");
			}
			if($pwd["paypwd"]!=umd5($paypwd)){
				$this->goAll("支付密码出错",1);
			}
			$data=M("mod_csc_sender_bankcard")->postData();
			$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
			foreach($chk as $k){
				if(empty($data[$k])){
					$this->goAll("请完善银行资料",1);
				}
			}
			$data["senderid"]=SENDERID;
			M("mod_csc_sender_bankcard")->insert($data);
			$this->goAll("银行卡添加成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$row=M("mod_csc_sender_bankcard")->selectRow("id=".$id);
			if($row["senderid"]!=SENDERID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_sender_bankcard")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_csc_sender_bankcard")->selectRow("id=".$id);
			if($row["senderid"]!=SENDERID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_sender_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>