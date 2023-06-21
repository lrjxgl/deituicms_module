<?php
class kefu_spmsgControl extends skymvc{
	 
	public function onDefault(){
		$kfid=MM("kefu","kefu")->kefuShopid();
		$userid=get("userid","i");
		$user=M("user")->getUser($userid,"userid,nickname,user_head");
		$kefu=MM("kefu","kefu")->get($kfid);
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			"kfid"=>$kfid,
			"kefu"=>$kefu
		));
		$this->smarty->display("kefu_spmsg/index.html");
	}
	
	public function onList(){
		$kfid=MM("kefu","kefu")->kefuShopid();
		$userid=get("userid","i");
		$start=get("per_page","i");
		$limit=12;
		$where=" userid=".$userid." AND kfid=".$kfid;
		$order="id DESC";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_kefu_spmsg")->select($ops,$rscount);
		if($list){
			$ids=[];
			foreach($list as $k=>$v){
				$v["timeago"]=timeago($v["dateline"]);
				$v['fileurl']=images_site($v["fileurl"]);
				$ids[]=$v["id"];
				$list[$k]=$v;
			}
			array_multisort($list,$ids,SORT_ASC);
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
	}
	
	public function onsave(){
		$userid=post("userid","i");
		$content=post("content","x");
		$fileurl=post("fileurl","h");
		$filetype=post("filetype","h");
		if($content=='' && $fileurl==''){
			$this->goAll("内容不能为空",1);
		}
		$kfid=MM("kefu","kefu")->kefuShopid();
		$dateline=time();
		//客服端
		M("mod_kefu_spmsg")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"dateline"=>time(),
			"kfid"=>$kfid,
			"ukey"=>"kefu",
			"fileurl"=>$fileurl,
			"filetype"=>$filetype
		));
		$row=M("mod_kefu_spmsg_index")->selectRow("userid=".$userid." AND kfid=".$kfid);
		if(!$row){
			M("mod_kefu_spmsg_index")->insert(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"kefu",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype
			));
		}else{
			M("mod_kefu_spmsg_index")->update(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"kefu",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype
			),"id=".$row["id"]);
		}
		//用户端
		M("mod_kefu_msg")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"dateline"=>time(),
			"kfid"=>$kfid,
			"ukey"=>"kefu",
			"fileurl"=>$fileurl,
			"filetype"=>$filetype
		));
		$row=M("mod_kefu_msg_index")->selectRow("userid=".$userid." AND kfid=".$kfid);
		if(!$row){
			M("mod_kefu_msg_index")->insert(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"kefu",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"isread"=>0
			));
		}else{
			M("mod_kefu_msg_index")->update(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"kefu",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"isread"=>0
			),"id=".$row["id"]);
		}
		$this->goAll("保存成功");
	}
	
	
	
}

?>