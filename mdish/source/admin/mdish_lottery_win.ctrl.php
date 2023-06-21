<?php
class mdish_lottery_winControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status in(0,1,2)";
		$url="moduleadmin.php?m=mdish_lottery_win";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" ltid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_mdish_lottery_win")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v["userid"];
				$pids[]=$v["ltid"];
			}
			$us=M("user")->getUserByIds($uids);
			$pros=MM("mdish","mdish_lottery")->getListByIds($pids);
			foreach($data as $k=>$v){
				$p=$pros[$v["ltid"]];
				$p["nickname"]=$v["nickname"];
				$p["address"]=$v["address"];
				$p["telephone"]=$v["telephone"];
				$data[$k]=$p;
				
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("mdish_lottery_win/index.html");
	}
	
	public function onAdd(){
		$sday=date("Ymd",time()-3600*24);
		$this->smarty->goAssign(array(
			"sday"=>$sday
		));
		$this->smarty->display("mdish_lottery_win/add.html");
	}
	public function onSetWin(){
		$sday=get("sday","i");
		$ymd=date("Ymd");
		if($sday==''){
			$this->goAll("无法开奖",1);
		}
		if($sday>=$ymd){
			$this->goAll("无法开奖",1);
		}
		$row=M("mod_mdish_lottery_setwin")->selectRow("sday=".$sday);
		if($row){
			$this->goAll("已经开奖了",1);
		}
		//获取参与抽奖用户
		M("mod_mdish_lottery_setwin")->insert(array(
			"sday"=>$sday
		)); 
		$uids=M("mod_mdish_lottery_join")->selectCols(array(
			"where"=>" sday=".$sday,
			"fields"=>"userid"
		));
		 
		//获取参与的抽奖产品
		$pids=M("mod_mdish_lottery")->selectCols(array(
			"where"=>" sday=".$sday,
			"fields"=>"ltid"
		));
		$plen=count($pids);
		shuffle($uids);
		for($i=0;$i<$plen;$i++){
			if(isset($uids[$i])){
				$join=M("mod_mdish_lottery_join")->selectRow(" sday=".$sday." AND userid=".$uids[$i]);
				M("mod_mdish_lottery_win")->insert(array(
					"userid"=>$uids[$i],
					"sday"=>$sday,
					"ltid"=>$pids[$i],
					"address"=>$join["address"],
					"nickname"=>$join["nickname"],
					"telephone"=>$join["telephone"]
				));
			}
			
		}
		
		$this->goAll("开奖成功");
	}
	
}
?>