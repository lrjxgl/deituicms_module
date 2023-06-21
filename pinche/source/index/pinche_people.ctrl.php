<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_peopleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			 
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=pinche_people&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ppid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_people")->select($option,$rscount);
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
			$this->smarty->display("pinche_people/my.html");
		}
		public function onAdd(){
			 
			$this->smarty->goassign(array(
				"a"=>1
			));
			$this->smarty->display("pinche_people/add.html");
		}
		public function onSendsms(){
			$telephone=get_post("telephone","h");
			if(!is_tel($telephone)){
				$this->goAll("电话号码出错",1);
			}
			$t=cache()->get("pinche_people_sms_expire_".$telephone);
			if($t){
				$this->goall("请过".(60-(time()-$t))."秒再发送",1);
			}
			$yzm=rand(111111,999999);
			 
			$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成密码找回";
			$content=array(
				"content"=>$content,
				"code"=>$yzm,
				"tpl"=>"code"
				
			);
			$key="pinche_people_sms".$telephone.$yzm;
			if(SMS_TEST==true){
				cache()->set($key,1,300);
				$this->goAll("短信已发送".$yzm);
			}
			$res=M("sms")->sendSms($telephone,$content);
			
			
			
			if($res){
				cache()->set($key,1,300);
				cache()->set("pinche_people_sms_expire_".$telephone,time(),60);
				$this->goAll("短信已发送，请在5分钟内找回密码");
			}else{
				$this->goAll("短信发送失败",1);
			}
		}
		public function onSave(){
			$ppid=get_post("ppid","i");
			$data=M("mod_pinche_people")->postData();
			if(empty($data["nickname"]) || empty($data["telephone"])){
				$this->goAll("请完善信息",1);
			}
			if(!is_tel($data["telephone"])){
				$this->goAll("电话号码出错",1);
			}
			$yzm=post("yzm","h");
			$key="pinche_people_sms".$data["telephone"].$yzm;
			if(!cache()->get($key)){
				$this->goAll("短信验证码出错",1);
			}
			$userid=M("login")->userid;
			$row=M("mod_pinche_people")->selectRow("userid=".$userid." AND status in(0,1,2) AND telephone='".$data["telephone"]."' ");
			if($row){
				$this->goAll("手机号码已经存在了");
			}
			$data["status"]=1;
			$data["userid"]=$userid;
			$data["dateline"]=time();
			M("mod_pinche_people")->insert($data);
			$this->goall("保存成功");
		}
		
		 
		
		public function onDelete(){
			$ppid=get_post('ppid',"i");
			$userid=M("login")->userid;
			$row=M("mod_pinche_people")->selectRow("ppid=".$ppid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_pinche_people")->update(array("status"=>11),"ppid=".$ppid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>