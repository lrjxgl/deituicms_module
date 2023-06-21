<?php
class pinche_tixianControl extends skymvc{
	public $k="pinche_driver";
	public function onDefault(){
		$account=MM("pinche","pinche_driver_account")->get(DRIVERID);
		$bankList=M("mod_pinche_bankcard")->select(array(
			"where"=>" driverid=".DRIVERID." AND status=1 "
		));
		$this->smarty->goAssign(array(
			"account"=>$account,
			"bankList"=>$bankList
		));
		$this->smarty->display("pinche_tixian/index.html");
	}
	public function onList(){
		$limit=20;
		$where= "k='pinche_driver' AND objectid=".DRIVERID;
		$url="/moduleshop.php?m=pinche_tixian&a=list";
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
		$this->smarty->display("pinche_tixian/list.html");
	}
	
	public function onSave(){
		$money=post('money',"h");
		if($money<100){
			$this->goAll("提现金额不能小于100元",1);
		}
		$account=MM("pinche","pinche_driver_account")->get(DRIVERID);
		 
		if($account['balance_money']<$money){
			$this->goAll("提现余额不足",2);
		}
		 
		M("tixian")->begin();
		$data=M("tixian")->postData();
		$bankid=post("bankid","i");
		if(empty($bankid)){
			$this->goAll("请选择提现账户");
		}
		$bank=M("mod_pinche_bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']=$this->k;
		$data['objectid']=DRIVERID;
		//减少余额
		MM("pinche","pinche_driver_account")->addMoney(array(
					"driverid"=>DRIVERID,
					"money"=>-$money,
					"content"=>"您提现".$money."元"
		));		 
		M("tixian")->insert($data);
		M("tixian")->commit();
		$this->goAll("提现成功，请等待处理",0,0,"/moduleshop.php?m=tixian");
	}
	 
	
}