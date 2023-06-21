<?php
class wmo2o_offpayControl extends skymvc{
	public function onDefault(){
		$start=get("per_page","i");
		$limit=24;
		$where=" shopid=".SHOPID." AND ispay=1 AND status in(0,1,2) ";
		$url="/moduleshop.php?m=wmo2o_offpay";
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"orderid DESC"
		);
		$rscount=true;
		$list=MM("wmo2o","wmo2o_offpay")->select($ops,$rscount);
		$uids=[];
		if(!empty($list)){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($list as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("wmo2o_offpay/index.html");
	}
}