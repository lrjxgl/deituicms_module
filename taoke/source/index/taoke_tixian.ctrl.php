<?php
class taoke_tixianControl extends skymvc{
	public $sw;
	public $k;
	public function __construct(){
		parent::__construct();	
		$this->k="mod_taoke_user";
		
	}
	public function onInit(){
		$this->sw=" k='".$this->k."' AND objectid=".M("login")->userid;
	}
	public function onList(){
		$limit=20;
		$where= $this->sw;
		$url="/module.php?m=taoke_tixian";
		$start=get('per_page','i');
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC",
			"where"=>$where,
		);
		$rscount=true;
		$data=M("tixian")->select($option,$rscount);
		
		if($data){
			$statuslist=M("tixian")->status_list();
			foreach($data as $k=>$v){
				$v['status_name']=$statuslist[$v['status']];
				$v['timeago']=timeago($v['dateline']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);	
		$per_page=$start+$limit;
	 	$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goassign(array(
			"list"=>$data,
			"per_page"=>$per_page
		));
		$this->smarty->display("taoke_tixian/list.html");
	}
	
	
	public function onDefault(){
		$userid=M("login")->userid; 
		$umoney=MM("taoke","taoke_user_money")->get($userid);
		$bankList=M("bankcard")->select(array(
			"where"=>"userid=".$userid." AND status=0 "
		));	
		$this->smarty->goassign(array(
			"umoney"=>$umoney,
			"bankList"=>$bankList
		));
		$this->smarty->display("taoke_tixian/index.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$money=post('money',"h");
		if($money<100){
			$this->goAll("提现金额不能小于100元",1);
		}
		$shop=M("mod_taoke_user_money")->selectRow("userid=".$userid);
		if($shop['balance']<$money){
			$this->goAll("提现余额不足",2);
		}
		$pp=M("user_password")->selectRow("userid=".$userid);
		if(!$pp){
			$this->goAll("请先设置支付密码",1,0,"/index.php?m=user&a=password");
		}
		$paypwd=post("paypwd","h");
		if(umd5($paypwd)!=$pp["paypwd"]){
			$this->goAll("支付密码出错",1);
		}
		M("tixian")->begin();
		$data=M("tixian")->postData();
		$bankid=post("bankid","i");
		if(empty($bankid)){
			$this->goAll("请选择提现账户");
		}
		$bank=M("bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']=$this->k;
		$data['objectid']=$userid;
		//减少余额
		MM("taoke","taoke_user_money")->addMoney(array(
					"userid"=>$userid,
					"balance"=>-$money,
					"content"=>"您提现".$money."元"
		));		 
		M("tixian")->insert($data);
		M("tixian")->commit();
		$this->goAll("提现成功，请等待处理",0,0,"/module.php?m=taoke_tixian");
	}
	
}

?>