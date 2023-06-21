<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_bankcardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=csc_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_bankcard")->select($option,$rscount);
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
			$this->smarty->display("csc_bankcard/index.html");
		}
		
		public function onAdd(){
		
			$bankList=MM("csc","csc_bankcard")->bankList();
			 
			$pp=MM("csc","csc_shop_paypwd")->get(SHOPID);
			if(!$pp){
				$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=csc_shop_paypwd");
			}
			 
			$this->smarty->goassign(array(
				 
				"bankList"=>$bankList
				 
			));
			$this->smarty->display("csc_bankcard/add.html");
		}
		
		public function onsendsms(){
			$user=M("mod_csc_shop_safephone")->selectRow(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"shopid,telephone"
			));
			$yzm=rand(1111,9999);
			$telephone=$user["telephone"];
			$exkey="csc_bankcardAdd".$telephone;
			$key="csc_bankcardAdd_sms".$telephone.$yzm;
			$t=cache()->get($exkey);
			if($t){
				$this->goall("请过".(60-(time()-$t))."秒再发送",1);
			}
			
			 
			$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成验证";
			$content=array(
				"code"=>$yzm,
				"tpl"=>"code",
				"content"=>$content
			);
			$res=M("sms")->sendSms($telephone,$content);
			
			
			if($res){
				cache()->set($key,1,300);
				cache()->set($exkey,time(),60);
				$this->goAll("短信已发送，请在5分钟内验证注册");
			}else{
				$this->goAll("短信发送失败",1);
			}
		}
		
		public function onSave(){
			$pp=MM("csc","csc_shop_paypwd")->get(SHOPID);
			if(!$pp){
				$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=csc_shop_paypwd");
			}
			$paypwd=post("paypwd","h");
			if(umd5($paypwd)!=$pp["paypwd"]){
				$this->goAll("支付密码出错",1);
			}
			 
			$data=M("mod_csc_bankcard")->postData();
			$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
			foreach($chk as $k){
				if(empty($data[$k])){
					$this->goAll("请完善银行资料",1);
				}
			}
			$data["shopid"]=SHOPID;
			M("mod_csc_bankcard")->insert($data);
			$this->goAll("银行卡添加成功");
			 
			
			 
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_csc_bankcard")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_csc_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>