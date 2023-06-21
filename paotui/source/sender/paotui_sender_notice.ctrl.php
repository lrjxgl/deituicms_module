<?php
class paotui_sender_noticeControl extends skymvc{
	public function onDefault(){
		$where=" senderid=".SENDERID;
		$start=get("per_page","i");
		$limit=24;
		$order="id DESC";
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order
		);
		$rscount=true;
		$list=M("mod_paotui_sender_notice")->select($ops,$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["status_name"]=$v["status"]==0?'未读':'已读';
				$v["timeago"]=timeago($v["dateline"]);
				$v['linkdata']=str2arr($v['linkurl']);
				$v['linkurl']=parseStrLink($v['linkurl']);
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"per_page"=>$per_page
		));
		$this->smarty->display("paotui_sender_notice/index.html");
	}
	
	public function onreadnotice(){
		$id=get("id","i");
		M("mod_paotui_sender_notice")->update(array(
			"status"=>1
		),"id=".$id." AND senderid=".SENDERID);
		$this->goAll("success");
	}
}