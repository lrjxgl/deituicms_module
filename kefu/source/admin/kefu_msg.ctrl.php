<?php
class kefu_msgControl extends skymvc{
	
	public function onDefault(){
		$start=get("per_page","i");
		$limit=12;
		$where="  status in(0,1,2)";
		$order="id DESC";
		$url="/moduleadmin.php?m=kefu_msg";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_kefu_msg")->select($ops,$rscount);
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
				$v["timeago"]=timeago($v["dateline"]);
				$v['fileurl']=images_site($v["fileurl"]);
				$v["user"]=$us[$v["userid"]];
				$v["kefu"]=$kfs[$v["kfid"]];
				$list[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("kefu_msg/index.html");
	}
	
	public function onDelete(){
		$id=get("id","i");
		M("mod_kefu_msg")->delete("id=".$id);
		$this->goAll("删除成功");
		
	}
	
}
