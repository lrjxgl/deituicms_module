<?php
class paotui_tixianControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		$bankList=M("mod_paotui_bankcard")->select(array(
			"where"=>" status in(0,1,2) AND senderid=".SENDERID
		));
		$this->smarty->goAssign(array(
			"sender"=>$sender,
			"bankList"=>$bankList
		));
		$this->smarty->display("paotui_tixian/index.html");
	}
	public function onList(){
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		$limit=20;
		$where= "k='paotui_sender' AND objectid=".SENDERID;
		$url="/sender.php?m=paotui_tixian&a=list";
		$start=get('per_page','i');
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC",
			"where"=>$where,
		);
		$rscount=true;
		$data=M("tixian")->select($option,$rscount);
		$statuslist=M("tixian")->status_list();
		if($data){
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
			"per_page"=>$per_page,
			"sender"=>$sender
		));
		$this->smarty->display("paotui_tixian/list.html");
	}
	
	public function onSave(){
		
		$bankid=post("bankid","i");
		$money=post("money","r");
		$bank=M("mod_paotui_bankcard")->selectRow("id=".$bankid);
		if(empty($bank)){
			$this->goAll("请选择提现账户",1);
		}
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		if($money<100){
			$this->goAll("提现金额不能小于100",1);
		}
		if($sender["money"]<$money){
			$this->goAll("提现金额不足",1);
		}
		
		M("tixian")->begin();
		MM("paotui","paotui_sender")->addMoney(array(
			"senderid"=>SENDERID,
			"money"=>-$money,
			"createtime"=>date("Y-m-d H:i:"),
			"content"=>"您申请提现￥".$money,
		));
		M("tixian")->insert(array(
			"k"=>"paotui_sender",
			"objectid"=>SENDERID,
			"money"=>$money,
			"dateline"=>time(),
			"info"=>"跑腿员提现",
			"yhk_name"=>$bank["yhk_name"],
			"yhk_haoma"=>$bank["yhk_haoma"],
			"yhk_huming"=>$bank["yhk_huming"],
			"yhk_address"=>$bank["yhk_address"],
			"telephone"=>$user["telephone"]
		));
		
		M("tixian")->commit();
		$this->goAll("提现申请成功");
	}
	
}