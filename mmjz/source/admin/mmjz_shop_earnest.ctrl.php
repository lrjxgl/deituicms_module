<?php
class mmjz_shop_earnestControl extends skymvc{
	 
	public function onDefault(){
		
		$url="/moduleadmin.php?m=mmjz_shop_earnest";
		$start=get("per_page","i");
		$limit=12;
		$shopid=get("shopid","i");
		$shopname=get("shopname","h");
		$where=" 1 ";
		if($shopid){
			$where.=" AND shopid=".$shopid;
		}
		if($shopname){
			$shopids=MM("mmjz","mmjz_shop")->selectCols(array(
				"where"=>" shopname like '%".$shopname."%' ",
				"fields"=>"shopid"
			));
			 
			if(empty($shopids)){
				$where="  1=2 ";
			}else{
				$where.=" AND shopid in("._implode($shopids).") " ;
			}
			 
		}
		$order="money DESC";
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order
		);
		$rscount=true; 
		$list=MM("mmjz","mmjz_shop_earnest")->select($ops,$rscount);
		if(!empty($list)){
			$shopids=[];
			foreach($list as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("mmjz","mmjz_shop")->getListByIds($shopids);
			foreach($list as $k=>$v){
				$v["shopname"]=$sps[$v["shopid"]]["shopname"];
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"pagelist"=>$pagelist, 
			"list"=>$list
		));
		$this->smarty->display("mmjz_shop_earnest/index.html");
	}
	
	public function onSave(){
		$money=post("money","i");
		$shopid=post("shopid","i");
		$content=post("content","h");
		if(empty($content)){
			$content="平台扣除商家保证金".$money."元";
		}
		$sp=MM("mmjz","mmjz_shop_earnest")->get($shopid);
		if($sp["money"]<$money){
			$this->goAll("商家保证金不足",1);
		}
		//减少余额
		MM("mmjz","mmjz_shop_earnest")->begin();
		MM("mmjz","mmjz_shop_earnest")->addMoney(array(
			"shopid"=>$shopid,
			"money"=>-$money,
			"content"=>$content
		)); 
		MM("mmjz","mmjz_shop_earnest")->commit();
		$this->goAll("扣除成功");
	}
	
	public function onOut(){
		$data=MM("mmjz","mmjz_shop_earnest")->get(SHOPID);
		$bankList=M("mod_mmjz_bankcard")->select(array(
			"where"=>"shopid=".SHOPID
		));	
		$this->smarty->goAssign(array(
			"data"=>$data,
			"bankList"=>$bankList
		));
		$this->smarty->display("mmjz_shop_earnest/out.html");
	}
	public function onOutSave(){
		$money=post("money","i");
		$data=MM("mmjz","mmjz_shop_earnest")->get(SHOPID);
		if($data["money"]<=$money){
			$this->goAll("保证金不足",1);
		}
		if($money<100){
			$this->goAll("资金小于100元不能提取",1);
		}
		$pp=MM("mmjz","mmjz_shop_paypwd")->get(SHOPID);
		if(!$pp){
			$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=mmjz_shop_paypwd");
		}
		$paypwd=get_post("paypwd","h");
		if(umd5($paypwd)!=$pp["paypwd"]){
			$this->goAll("支付密码出错",1);
		}
		M("tixian")->begin();		
		$data=M("tixian")->postData();
		$bankid=get_post("bankid","i");
		if(empty($bankid)){
			$this->goAll("请选择提现账户");
		}
		$bank=M("mod_mmjz_bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']="mod_mmjz_shop";
		$data['objectid']=SHOPID;
		$data["info"]="提取商家保证金".$money."元";
		//减少余额
		MM("mmjz","mmjz_shop_earnest")->addMoney(array(
			"shopid"=>SHOPID,
			"money"=>-$money,
			"content"=>"提取商家保证金".$money."元"
		)); 	 
		M("tixian")->insert($data);
		M("tixian")->commit();
		
		$this->goAll("保证金提取成功");
	}
	
}
