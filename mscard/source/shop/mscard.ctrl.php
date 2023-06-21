<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mscardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			$this->smarty->display("mscard/index.html");
		}
		
		public function onList(){
			$where=" shopid=".SHOPID;
			$url="/moduleshop.php?m=mscard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mscard")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("mscard/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_mscard")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mscard/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_mscard")->selectRow(array("where"=>"id={$id}"));				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mscard/add.html");
		}
		
		public function onSendSms(){
			$telephone=get('telephone','h');
			 
			$tkey="mscard_sendsms_time";
			if(cache()->get($tkey)){
				$this->goALl("请稍后再发送",1);
			}
			if(!cache()->get($key)){
				$num=rand(1000,9999);				
				$site=M("sites")->selectRow(array("order"=>"siteid ASC","limit"=>1));
				$res=M("email")->sendSms($telephone,"【".$site['sitename']."】验证码：".$num."，请您5分钟内完成验证");
				if($res){
					cache()->set($tkey,1,60);
					$key="mscard_sendsms".$telephone.$num;
					cache()->set($key,1,300);
					$this->goAll("短信已发送，请在5分钟内验证注册");
				}else{
					$this->goAll("短信发送失败",1);
				}
			} 
		}
		
		public function onSave(){
			
 
			$data["nickname"]=post("nickname","h");		 
		
			$data["telephone"]=post("telephone","h");
			$data["idcard"]=post("idcard","h");
			$data["shopid"]=SHOPID;		
			$data["dateline"]=time();
			$data["description"]=post("description","h");
			$data["status"]=0;
			//验证短信
			$smsYzm=post('smsYzm','h');
			$key="mscard_sendsms".$data['telephone'].$smsYzm;
			if(!cache()->get($key)){
				$this->goAll("手机验证失败",1);
			}
			$row=M("mod_mscard")->selectRow("shopid=".SHOPID." AND telephone='".$data['telephone']."'");
			if($row){
				$this->goAll("该手机号码已经办理过了",1);
			}
			$user=M("user")->selectRow("telephone='".$data['telephone']."'");
			if($user){
				$data['userid']=$user['userid'];
			}
		
			M("mod_mscard")->insert($data);
			$this->goall("开卡成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$statusget_post("sstatus"i");
			M("mod_mscard")->update(array("status=>$sstatus"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_mscard")->update(array("status=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecharge(){
			$money=post('money','h');
			$telephone=post('telephone','h');
			$card=M("mod_mscard")->selectRow("telephone='".$telephone."' AND shopid=".SHOPID);
			if(!$card){
				$this->goAll("会员卡不存在",1);
			}
			if($money<0){
				$this->goAll("金额不能小于0",1);
			}
			$rmoney=$card['money']+$money;
			M("mod_mscard")->update(array(
				"money"=>$rmoney
			),"id=".$card['id']);
			M("mod_mscard_log")->insert(array(
				"cardid"=>$card['id'],
				"content"=>"充值{$money},原来{$card['money']}元，现在{$rmoney}元。",
				"dateline"=>time(),
				"shopid"=>SHOPID,
				"userid"=>$card['userid'],
				"money"=>$money,
				"type"=>"money"
			));
			
			$this->goAll("充值成功",0,$rmoney);
		}
		
		
	}

?>