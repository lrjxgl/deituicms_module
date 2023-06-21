<?php
class csc_sender_tixianControl extends skymvc{
	public $sw;
	public $k;
	public function __construct(){
		parent::__construct();	
		$this->k="mod_csc_sender";
		$this->sw=" k='".$this->k."' AND objectid=".SENDERID;
	}
	
	public function onList(){
		$limit=20;
		$where= $this->sw;
		 
		$url="/modulesender.php?m=csc_sender_tixian";
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
		$this->smarty->display("csc_sender_tixian/list.html");
	}
	
	
	public function onDefault(){
		$sender=MM("csc","csc_sender")->get(SENDERID);
		$senderMoney=MM("csc","csc_sender_money")->get(SENDERID);
		 
		$last=M("tixian")->selectRow(array(
			"order"=>"id DESC",
			"where"=>$this->sw,
			"limit"=>1
		));
		$bankList=M("mod_csc_sender_bankcard")->select(array(
			"where"=>"senderid=".SENDERID." AND status=0 "
		));	
		$this->smarty->goassign(array(
			"sender"=>$sender,
			"senderMoney"=>$senderMoney,
			"last"=>$last,
			"bankList"=>$bankList
		));
		$this->smarty->display("csc_sender_tixian/index.html");
	}
	
	public function onSave(){
		
		$money=post('money',"h");
		if($money<100){
			$this->goAll("提现金额不能小于100元",1);
		}
		$sender=M("mod_csc_sender_money")->selectRow("senderid=".SENDERID);
		if($sender['balance']<$money){
			$this->goAll("提现余额不足",2);
		}
		$pp=M("mod_csc_sender_paypwd")->selectRow("senderid=".SENDERID);
		if(!$pp){
			$this->goAll("请先设置支付密码",1,0,"/modulesender.php?m=csc_sender_paypwd");
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
		$bank=M("mod_csc_sender_bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']=$this->k;
		$data['objectid']=SENDERID;
		//减少余额
		MM("csc","csc_sender_money")->addMoney(array(
			"senderid"=>SENDERID,
			"balance"=>-$money,
			"content"=>"您提现".$money."元"
		));		 
		M("tixian")->insert($data);
		M("tixian")->commit();
		$this->goAll("提现成功，请等待处理",0,0,"/sender.php?m=csc_sender_tixian");
	}
	
}

?>