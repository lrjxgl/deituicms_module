<?php
class kefu_msg_indexControl extends skymvc{
	
	public function onDefault(){
		$tablename=get("tablename","h");
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=12;
		
		$where=" userid=".$userid."";
		if($tablename){
			$where.=" AND tablename='".$tablename."' ";
		}
		$order="dateline DESC";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_kefu_msg_index")->select($ops,$rscount);
		if($list){
			$uids=[];
			$kfids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
				$kfids[]=$v["kfid"];
			}
			$us=M("user")->getUserByIds($uids);
			$kfs=MM("kefu","kefu")->getListByIds($kfids);
			foreach($list as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				$v["kefu"]=$kfs[$v["kfid"]];
				$v["timeago"]=timeago($v["dateline"]);
				$list[$k]=$v;
			}
		}
		 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"tablename"=>$tablename, 
			"per_page"=>$per_page
		));
		$this->smarty->display("kefu_msg_index/index.html");
	}
	
}