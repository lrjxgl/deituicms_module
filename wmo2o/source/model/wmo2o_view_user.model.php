<?php
class wmo2o_view_userModel extends model{
	public $table="mod_wmo2o_view_user";
	public function __construct(){
		parent::__construct();
	}
	public function add($shopid,$userid){
		$shopid=intval($shopid);
		$userid=intval($userid);
		if(!$shopid || !$userid) return false;
		$row=$this->selectRow(" shopid=".$shopid." AND userid=".$userid);
		if($row){
			 
			$this->update(array(
				"lasttime"=>date("Y-m-d H:i:s"),
			),"id=".$row['id']);
		}else{
			$this->insert(array(
				"shopid"=>$shopid,
				"userid"=>$userid,
				"lasttime"=>date("Y-m-d H:i:s"),
				"createtime"=>date("Y-m-d H:i:s"),
			));
			//发送通知
			$user=M("user")->getUser($userid);
			MM("wmo2o","wmo2o_push")->sendShop(array(
				"table"=>"mod_wmo2o_shop",
				"shopid"=>$shopid,
				"content"=>array(
					"content"=>"有新用户“".$user['nickname']."”访问了您的店铺了",
					"first"=>"有新用户访问了您的店铺了",
					"keyword1"=>$user['nickname'],
					"keyword2"=>date("Y-m-d H:i:s"),
					"remark"=>""
				),
				"url"=>HTTP_HOST."/shopadmin.php?m=wmo2o_view_user&type=view",
				"linkurl"=>array(
					"path"=>"/moduleshop.php",
					"m"=>"wmo2o_view_user",
					"a"=>"view",
					"param"=>""
				),	
				"template_id"=>"newuser"
			));
		}
	}
	
}