<?php
class householdControl extends skymvc{
	
	public function onDefault(){
		 
		$sender=MM("household","household_sender")->get(SENDERID);
		if(!$sender["isauth"]){
			$this->goall("请先认证",11,0,"/sender.php?m=household_sender_auth");
		}
		$config=MM("household","mod_household_config")->selectRow("1");
		//cityid
		$cityid=M("city")->getCityid();
		$city=M("city")->selectRow("id=".$cityid);
		$this->smarty->goAssign(array(
			"sconfig"=>$config,
			"city"=>$city,
			"cityid"=>$cityid
		));
		$this->smarty->display("index.html");
	}
	
	public function onList(){
		$catid=get("catid","i");
		$catList=MM("household","household_category")->children(0);
		
		$where=" status=0 AND ispay=1 AND senderid=0 ";
		$catids=MM("household","household_category")->id_family($catid);
		if($catid){
			$where.=" AND catid in("._implode($catids).") ";
		}
		
		$config=MM("household","mod_household_config")->selectRow("1");
		
		if($config["dtime"]>0){
			$dtime=date("Y-m-d H:i:s",time()-$config["dtime"]*60);
			$where.=" AND createtime<'".$dtime."'";
		}
		if($config["morecity"]){
			$cityid=M("city")->getCityid();
			$where.=" AND cityid=".$cityid;
		}
		$list=MM("household","household_order")->select(array(
			"where"=>$where,
			"order"=>"orderid DESC"
		));
		if($list){
			foreach($list as $v){
				$oids[]=$v["orderid"];
				$uids[]=$v["userid"];
			}
			$ods=MM("household","household_order_data")->getListByOrderIds($oids);
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["cat_name"]="hi";
				$v['addr']=$ods[$v['orderid']]['address'];
				$v['prolist']=$ods[$v['orderid']]['prolist'];
				$v["status_name"]=MM("household","household_order")->getStatus($v);
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["status_name"]=MM("household","household_order")->getStatus($v);
				$list[$k]=$v;
			}
			 
		}
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"catList"=>$catList,
			"catid"=>$catid
		));
		 
	
	}
	
	public function onOrder(){
		$orderid=get("orderid","i");
		$household=M("mod_household_order")->selectRow("orderid=".$orderid);
		if($household["senderid"]!=0 || $household["ispay"]!=1){
			$this->goAll("该单已经被抢了",1);
		}
		M("mod_household_order")->begin();
		
		M("mod_household_order")->update(array(	 
			"senderid"=>SENDERID
		),"orderid=".$orderid);
		$sender=MM("household","household_sender")->selectRow(array(
			"where"=>" senderid=".SENDERID,
			"fields"=>"senderid,truename,telephone"
		));
		$content=$sender["truename"]."抢单成功";
		 
		MM("household","household_order_log")->insert(array(
			 
			"orderid"=>$orderid,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>$content,
			"utype"=>"sender",
			"userid"=>SENDERID
		)); 
		//通知用户
		M("notice")->add(array(
			"content"=>"您的家政订单有人接了",
			"userid"=>$household["userid"],
			"linkurl"=>array(
				"m"=>"household_order",
				"a"=>"show",
				"param"=>"orderid=".$household["orderid"],
				"path"=>"/module.php"
			)
		));
		M("mod_household_order")->commit();
		$this->goAll("抢单成功");
	}
	
	
	
}
?>