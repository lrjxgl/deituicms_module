<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_bankcardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=gread_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_bankcard")->select($option,$rscount);
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
			$this->smarty->display("gread_bankcard/index.html");
		}
		
		public function onAdd(){

			$bankList=MM("gread","gread_bankcard")->bankList();
			 
			
			$this->smarty->goassign(array(
				 
				"bankList"=>$bankList,
				"user"=>$user
			));
			$this->smarty->display("gread_bankcard/add.html");
		}
		
		 
		
		public function onSave(){
			$data=M("mod_gread_bankcard")->postData();
			$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
			foreach($chk as $k){
				if(empty($data[$k])){
					$this->goAll("请完善银行资料",1);
				}
			}
			$pp=MM("gread","gread_shop_paypwd")->get(SHOPID);
			if(!$pp){
				$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=gread_shop_paypwd");
			}
			$paypwd=post("paypwd","h");
			if(umd5($paypwd)!=$pp["paypwd"]){
				$this->goAll("支付密码出错",1);
			}
			$data["shopid"]=SHOPID;
			M("mod_gread_bankcard")->insert($data);
			$this->goAll("银行卡添加成功");
			 
		}
		
		 
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_gread_bankcard")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_gread_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>