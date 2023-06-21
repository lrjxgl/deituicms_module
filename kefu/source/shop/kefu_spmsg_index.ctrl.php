<?php
class kefu_spmsg_indexControl extends skymvc{
	
	public function onDefault(){
		$kfid=MM("kefu","kefu")->kefuShopid();
		$start=get("per_page","i");
		$limit=12;
		
		$where=" kfid=".$kfid."";
		 
		$order="dateline DESC";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_kefu_spmsg_index")->select($ops,$rscount);
		if($list){
			$uids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				$v["timeago"]=timeago($v["dateline"]);
				$v['fileurl']=images_site($v["fileurl"]);
				$list[$k]=$v;
			}
		}
		$kefu=M("mod_kefu")->selectRow("kfid=".$kfid);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"kefu"=>$kefu,
			"per_page"=>$per_page
		));
		$this->smarty->display("kefu_spmsg_index/index.html");
	}
	
}