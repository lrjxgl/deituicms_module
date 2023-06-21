<?php
class b2b_hotControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("b2b_hot/index.html");
	}
	public function onApi(){
		$res=M("mod_b2b_shop_hotvip")->select(array(
			"where"=>"isfinish=0",
			"order"=>"spid DESC",
			"fields"=>"shopid,spid",
			"limit"=>100
		));
		$shopids=[];
		$spids=[];
		if(!empty($res)){
			foreach($res as $rs){
				if(!in_array($rs["shopid"],$shopids)){
					$shopids[]=$rs["shopid"];
					$spids[$rs["shopid"]]=$rs["spid"];
				}
				
			}
		}
		
		$list=[];
		 
		if(!empty($shopids)){
			shuffle($shopids);  
			$shopids=array_slice($shopids,0,12);
			$list=MM("b2b","b2b_shop")->DselectWindow(array(
				"where"=>" shopid in("._implode($shopids).") "
			));
			if(!empty($list)){
				foreach($list as $k=>$v){
					$v["spid"]=$spids[$v["shopid"]];
					$list[$k]=$v;
				}
			}
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list
			)
			
		));
		
	}
	
	public function onHotvipGo(){
		$spid=get("spid","i");
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		MM("b2b","b2b_shop_hotvip")->addUser(array(
			"spid"=>$spid,
			"shopid"=>$shopid,
			"userid"=>$userid
		));
		$this->goAll("success");
	}
}