<?php
class im_group_user_applyControl extends skymvc{
	
	public  function onDefault(){
		
		$where=" 1 ";
		$type=get("type","h");
		switch($type){
			case "new":
				$where=" status=0 ";
				break;
			case "pass":
				$where=" status=1 ";
				break;
			case "forbid":
				$where=" status=2 ";
				break;
		}
		$list=M("mod_im_group_user_apply")->select(array(
			"where"=>$where,
			"order"=>" id DESC",
			"limit"=>24
		));
		if(!empty($list)){
			$uids=[];
			$ids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
				$gids[]=$v["groupid"];
			}
			$us=M("user")->getUserByIds($uids);
			$gs=MM("im","im_group")->getListByIds($gids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["group"]=$gs[$v["groupid"]];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"type"=>$type
		));
		$this->smarty->display("im_group_user_apply/index.html");
	}
	public function onPass(){
		$id=get("id","i");
		M("mod_im_group_user_apply")->update(array(
			"status"=>1
		),"id=".$id);
		$row=M("mod_im_group_user_apply")->selectRow("id=".$id);
		$g=M("mod_im_group_user")->selectRow("userid=".$row["userid"]." AND groupid=".$row["groupid"]);
		if(!$g){
			M("mod_im_group_user")->insert(array(
				"userid"=>$row["userid"],
				"groupid"=>$row["groupid"],
				"dateline"=>time()
			));
			M("mod_im_group")->changenum("user_num",1,"groupid=".$row["groupid"]);
		}
		$this->goAll("审核完成");
	}
	public function onForbid(){
		$id=get("id","i");
		M("mod_im_group_user_apply")->update(array(
			"status"=>2
		),"id=".$id);
		$this->goAll("审核完成");
	}
	
}
