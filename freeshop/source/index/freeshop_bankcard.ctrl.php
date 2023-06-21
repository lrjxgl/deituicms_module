<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class freeshop_bankcardControl extends skymvc{
		public $shopid;
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			$userid=M("login")->userid;
			$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
			if(empty($shop)){
				$this->goAll("暂无权限",1);
			}
			$this->shopid=$shop["shopid"]; 
		}
		public function onDefault(){
			 
			$where="status in(0,1,2) AND shopid=".$this->shopid;
			$url="/module.php?m=freeshop_bankcard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_freeshop_bankcard")->select($option,$rscount);
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
			$this->smarty->display("freeshop_bankcard/index.html");
		}
		
		public function onAdd(){
			
			$bankList=MM("freeshop","freeshop_bankcard")->bankList();
			$userid=M("login")->userid; 
			$user=M("user")->selectRow(array(
				"where"=>" userid=".$userid,
				"fields"=>"userid,telephone"
			));
			if(empty($user["telephone"])){
				$this->goAll("请先绑定手机号码",1,0,"/index.php?m=user&a=bind");
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"bankList"=>$bankList,
				"user"=>$user
			));
			$this->smarty->display("freeshop_bankcard/add.html");
		}
		
		public function onsendsms(){
			$userid=M("login")->userid;
			$user=M("user")->selectRow(array(
				"where"=>" userid=".$userid,
				"fields"=>"userid,telephone"
			));
			$yzm=rand(1111,9999);
			$telephone=$user["telephone"];
			$exkey="freeshop_bankcardAdd".$telephone;
			$key="freeshop_bankcardAdd_sms".$telephone.$yzm;
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
			
			
			if(defined("SMS_TEST") && SMS_TEST){
				$res=true;
				cache()->set($key,1,300);
				cache()->set($exkey,time(),60);
				$this->goAll("验证码".$yzm);
			}
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
			$yzm=get_post('yzm','h');
			$userid=M("login")->userid;
			$user=M("user")->selectRow(array(
				"where"=>" userid=".$userid,
				"fields"=>"userid,telephone"
			));
			$telephone=$user["telephone"];
			$key="freeshop_bankcardAdd_sms".$telephone.$yzm;
			
			if(cache()->get($key)){
				$data=M("mod_freeshop_bankcard")->postData();
				$chk=array("yhk_name","yhk_haoma","yhk_huming","yhk_address");
				foreach($chk as $k){
					if(empty($data[$k])){
						$this->goAll("请完善银行资料",1);
					}
				}
				$data["shopid"]=$this->shopid;
				M("mod_freeshop_bankcard")->insert($data);
				$this->goAll("银行卡添加成功");
			}else{
				$this->goAll("短信验证码认证失败",1);
			}
			 
			
			 
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_freeshop_bankcard")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_freeshop_bankcard")->update(array("status"=>11),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>