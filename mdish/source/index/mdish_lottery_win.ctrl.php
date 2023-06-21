<?php
class mdish_lottery_winControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status in(0,1,2)";
		$url="module.php?m=mdish_lottery_win";
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
	
	public function onMy(){
		$userid=M("login")->userid;
		$where="  userid=".$userid;
		$url="module.php?m=mdish_lottery_win&a=my";
		$type=get("type","h");
		switch($type){
			case "use":
				$where.=" AND status=0 AND isfinish=0 ";
				break;
			case "finish":
				$where.=" AND   isfinish=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2) ";
				break;
		}
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
				
				$pids[]=$v["ltid"];
			}
			
			$pros=MM("mdish","mdish_lottery")->getListByIds($pids);
			foreach($data as $k=>$v){
				$p=$pros[$v["ltid"]];
				if($v["isfinish"]){
					$p["status_name"]="已领";
				}elseif($v["status"]>1){
					$p["status_name"]="已废弃";
				}else{
					$p["status_name"]="待领";
				}
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
		$this->smarty->display("mdish_lottery_win/my.html");
	} 
}
?>