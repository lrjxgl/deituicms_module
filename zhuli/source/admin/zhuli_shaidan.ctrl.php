<?php
class zhuli_shaidanControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$type=get("type","h");
		$url="/moduleadmin.php?m=zhuli_shaidan&type=".$type;
		$rscount=true;
		$start=get('per_page','i');
		$limit=24;
		$where=" status in(0,1,2) ";
		switch($type){
			case "new":
				$where="status=0";
				break;
			case "pass":
				$where="status=1";
				break;
			case "gold":
				$where="status=1 AND gold=0 ";
				break;
			case "forbid":
				$where="status=2";
				break;
			default:
				 
				$type="all";
				break;
		}
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$data=M("mod_zhuli_shaidan")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			$statusList=array(
				0=>"新晒单",
				1=>"已通过",
				2=>"已禁止",
				11=>"已删除"
			);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$v['imgsdata'] && $v['imgsdata']=explode(",",$v['imgsdata']);
				$v["status_name"]=$statusList[$v["status"]];
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"pagelist"=>$pagelist,
			"type"=>$type
		));
		$this->smarty->display("zhuli_shaidan/index.html");
	}
	
	public function onSendGold(){
		$id=post("id","i");
		$row=M("mod_zhuli_shaidan")->selectRow("id=".$id);
		if($row["status"]!=1){
			$this->goALl("未通过审核",1);
		}
		$gold=post("gold","i");
		if($gold==0 || $row["gold"]){
			$this->goALl("无法赠送",1);
		}
		if($row["userid"]){
			M("user")->addMoney(array(
				"userid"=>$row["userid"],
				"gold"=>$gold,
				"content"=>"您助力砍价晒单奖励".$gold."个金币"
			));
			M("mod_zhuli_shaidan")->update(array(
				"gold"=>$gold
			),"id=".$row["id"]);
		}
		$this->goALl("success");
	}
	
}
?>