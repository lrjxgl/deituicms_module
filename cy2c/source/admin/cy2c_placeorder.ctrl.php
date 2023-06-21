<?php 
class cy2c_placeorderControl extends skymvc{
	
	public function onDefault(){
		$url="/moduleadmin.php?m=cy2c_placeorder";
		$start=get("per_page","i");
		$limit=24;
		$where="1";
		$type=get("type","h");
		switch($type){
			case "new":
				$where=" isfinish=0 ";
				break;
			case "finish":
				$where=" isfinish=1 ";
				break;
			default :
				break;
		}
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"poid DESC"
		);
		$rscount=true;
		$list=M("mod_cy2c_placeorder")->select($ops,$rscount);
		if(!empty($list)){
			$plids=[];
			foreach($list as $v){
				$plids[]=$v["placeid"];
			}
			$places=MM("cy2c","cy2c_place")->getListByIds($plids);
			foreach($list as $k=>$v){
				$v["place"]=$places[$v["placeid"]];
				$v["stime"]=date("H:i",strtotime($v["createtime"]));
				$money=M("mod_cy2c_order_product")->selectOne(array(
					"where"=>"poid=".$v["poid"]." AND ispay=1 AND status in(0,1,2,3) ",
					"fields"=>" sum(price*amount) as ct "
				));
				$v["money"]=!$money?0:$money;
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("cy2c_placeorder/index.html");
	}
	
	public function onProduct(){
		$poid=get("poid","i");
		$list=M("mod_cy2c_order_product")->select(array(
			"where"=>" poid=".$poid."  AND ispay=1 AND status in(0,1,2,3) "
		));
		if(!empty($list)){
			$proids=[];
			foreach($list as $v){
				$proids[]=$v["productid"];
			}
			$statusList=array(
				0=>"待接单",
				1=>"下锅",
				2=>"配送",
				3=>"已上菜",
				4=>"已完成"
			);
			$pros=MM("cy2c","cy2c_product")->getListByIds($proids);
			foreach($list as $k=>$v){
				$v["product"]=$pros[$v["productid"]];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		$this->goAll("success",0,array(
			"list"=>$list
		));
		
	}
	
	public function onFinish(){
		$poid=get("poid","i");
		$po=M("mod_cy2c_placeorder")->selectRow("poid=".$poid);
		if(empty($po)){
			$this->goAll("订单不存在",1);
		}
		if($po["isfinish"]){
			$this->goAll("已经结算了",1);
		}
		M("mod_cy2c_placeorder")->begin();
		M("mod_cy2c_placeorder")->update(array(
			"isfinish"=>1
		),"poid=".$poid);
		//
		M("mod_cy2c_order_product")->update(array(
			"isfinish"=>1
		),"poid=".$poid);
		M("mod_cy2c_placeorder")->commit();
		$this->goAll("确认完成");
	}
	
	
}