<?php
class b2b_tixianControl extends skymvc{
	public $sw;
	public $k;
	public function __construct(){
		parent::__construct();	
		$this->k="mod_b2b_shop";
		$this->sw=" k='".$this->k."' AND objectid=".SHOPID;
	}
	
	public function onList(){
		$limit=20;
		$where= $this->sw;
		$url="/moduleshop.php?m=b2b_tixian";
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
		$this->smarty->display("b2b_tixian/list.html");
	}
	
	
	public function onDefault(){
		$shop=M("mod_b2b_shop")->selectRow("shopid=".SHOPID);
		$shopmoney=MM("b2b","b2b_shop_money")->get(SHOPID);
		$last=M("tixian")->selectRow(array(
			"order"=>"id DESC",
			"where"=>$this->sw,
			"limit"=>1
		));
		$bankList=M("mod_b2b_bankcard")->select(array(
			"where"=>"shopid=".SHOPID
		));	
		$this->smarty->goassign(array(
			"shop"=>$shop,
			"shopmoney"=>$shopmoney,
			"last"=>$last,
			"bankList"=>$bankList
		));
		$this->smarty->display("b2b_tixian/index.html");
	}
	
	public function onSave(){
		
		$money=post('money',"h");
		if($money<100){
			$this->goAll("提现金额不能小于100元",1);
		}
		$shop=M("mod_b2b_shop_money")->selectRow("shopid=".SHOPID);
		if($shop['balance']<$money){
			$this->goAll("提现余额不足",2);
		}
		$pp=MM("b2b","b2b_shop_paypwd")->get(SHOPID);
		if(!$pp){
			$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=b2b_shop_paypwd");
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
		$bank=M("mod_b2b_bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']=$this->k;
		$data['objectid']=SHOPID;
		//减少余额
		MM("b2b","b2b_shop_money")->addMoney(array(
					"shopid"=>SHOPID,
					"balance"=>-$money,
					"content"=>"您提现".$money."元"
		));		 
		M("tixian")->insert($data);
		M("tixian")->commit();
		$this->goAll("提现成功，请等待处理",0,0,"/moduleshop.php?m=tixian");
	}
	
}

?>