<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotui_bankcardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND senderid=".SENDERID;
			$url="/sender.php?m=paotui_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui_bankcard")->select($option,$rscount);
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
			$this->smarty->display("paotui_bankcard/index.html");
		}
		
		public function onAdd(){

			$bankList=MM("paotui","paotui_bankcard")->bankList();

			$this->smarty->goassign(array(
				"data"=>$data,
				"bankList"=>$bankList,
				"user"=>$user
			));
			$this->smarty->display("paotui_bankcard/add.html");
		}
 
		
		public function onSave(){
			$yzm=get_post('yzm','h');
			$user=M("mod_paotui_sender_safephone")->selectRow(array(
				"where"=>" senderid=".SENDERID,
				"fields"=>"senderid,telephone"
			));
			$data=M("mod_paotui_bankcard")->postData();
			$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
			foreach($chk as $k){
				if(empty($data[$k])){
					$this->goAll("请完善银行资料",1);
				}
			}
			$data["senderid"]=SENDERID;
			$data["status"]=1;
			M("mod_paotui_bankcard")->insert($data);
			$this->goAll("银行卡添加成功");
			
			 
		}
		
		 
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_paotui_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>