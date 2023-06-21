<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class olprint_bankcardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=olprint_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_bankcard")->select($option,$rscount);
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
			$this->smarty->display("olprint_bankcard/index.html");
		}
		
		public function onAdd(){

			$bankList=MM("olprint","olprint_bankcard")->bankList();
			 
			$user=M("mod_olprint_shop_safephone")->selectRow(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"shopid,telephone"
			));
			if(empty($user["telephone"])){
				$this->goAll("请先绑定手机号码",1,0,"/moduleshop.php?m=olprint_shop_safephone&a=bind");
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"bankList"=>$bankList,
				"user"=>$user
			));
			$this->smarty->display("olprint_bankcard/add.html");
		}
		
		 
		
		public function onSave(){
			$paypwd=post("paypwd","h");
			$user=MM("olprint","olprint_shop_paypwd")->get(SHOPID);
			if($user["paypwd"]!=umd5($paypwd)){
				$this->goAll("支付密码出错",1);
			}
			$data=M("mod_olprint_bankcard")->postData();
			$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
			foreach($chk as $k){
				if(empty($data[$k])){
					$this->goAll("请完善银行资料",1);
				}
			}
			$data["shopid"]=SHOPID;
			M("mod_olprint_bankcard")->insert($data);
			$this->goAll("银行卡添加成功");
			
			 
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_olprint_bankcard")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_olprint_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>