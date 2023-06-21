<?php
class kefu_msgControl extends skymvc{
	 
	public function onDefault(){
		$kfid=get("kfid","i");
		if(!$kfid){
			$tablename=get_post("tablename","h");
			$objectid=get_post("objectid","i");
			$kefu=MM("kefu","kefu")->getByTablename($tablename,$objectid);
			$kfid=$kefu["kfid"];
		}else{
			$kefu=MM("kefu","kefu")->get($kfid);
		}
		if(empty($kefu)){
			$this->goAll("客服不在线",1);
		}
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head");
		
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			"kfid"=>$kfid,
			"kefu"=>$kefu
		));
		$this->smarty->display("kefu_msg/index.html");
	}
	
	public function onList(){
		$kfid=get("kfid","i");
		if(!$kfid){
			$tablename=get_post("tablename","h");
			$objectid=get_post("objectid","i");
			$kefu=MM("kefu","kefu")->getByTablename($tablename,$objectid);
			$kfid=$kefu["kfid"];
		}else{
			$kefu=MM("kefu","kefu")->get($kfid);
		}
		$userid=M("login")->userid;
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
		$list=M("mod_kefu_msg")->select($ops,$rscount);
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
		$userid=M("login")->userid;
		$content=post("content","x");
		$kfid=post("kfid","i");
		$dateline=time();
		$fileurl=post("fileurl","h");
		$filetype=post("filetype","h");
		if($content=='' && $fileurl==''){
			$this->goAll("内容不能为空",1);
		}
		$kefu=M("mod_kefu")->selectRow("kfid=".$kfid);
		$tablename=$kefu["tablename"];
		//客服端
		M("mod_kefu_spmsg")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"dateline"=>time(),
			"kfid"=>$kfid,
			"ukey"=>"user",
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
				"ukey"=>"user",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"isread"=>0,
				"tablename"=>$tablename
			));
		}else{
			M("mod_kefu_spmsg_index")->update(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"user",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"isread"=>0,
				"tablename"=>$tablename
				
			),"id=".$row["id"]);
		}
		//用户端
		M("mod_kefu_msg")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"dateline"=>time(),
			"kfid"=>$kfid,
			"ukey"=>"user",
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
				"ukey"=>"user",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"tablename"=>$tablename
			));
		}else{
			M("mod_kefu_msg_index")->update(array(
				"userid"=>$userid,
				"content"=>$content,
				"dateline"=>time(),
				"kfid"=>$kfid,
				"ukey"=>"user",
				"fileurl"=>$fileurl,
				"filetype"=>$filetype,
				"tablename"=>$tablename
			),"id=".$row["id"]);
		}
		$this->goAll("保存成功");
	}
	
	
	
}

?>