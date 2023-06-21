<?php
class im_group_userControl extends skymvc{
	
	public  function onDefault(){
		$url="/moduleadmin.php?m=im_group_user";
		$groupid=get("groupid","i");
		$group=M("mod_im_group")->selectRow("groupid=".$groupid);
		$where=" groupid=".$groupid;
		$start=get("per_page","i");
		$limit=24;
		$order="id DESC";
		$rscount=true;
		$list=M("mod_im_group_user")->select(array(
			"where"=>$where,
			"order"=>" id DESC",
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		if(!empty($list)){
			$uids=[];
			$ids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
				
			}
			$us=M("user")->getUserByIds($uids);
			
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"type"=>$type,
			"group"=>$group,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("im_group_user/index.html");
	}
	
	public function onDelete(){
		$id=get("id","i");
		$row=M("mod_im_group_user")->selectRow("id=".$id);
		M("mod_im_group")->changenum("user_num",-1,"groupid=".$row["groupid"]);
		M("mod_im_group_user")->delete("id=".$id); 
		$this->goAll("success");
		
	}
	
}