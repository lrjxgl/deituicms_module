<?php
class fsw_userControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		$fswUser=MM("fsw","fsw_user")->get($userid);
		$actNum=M("mod_fsw_match_user")->getCount("userid=".$userid);
		$this->smarty->goAssign(array(
			"user"=>$user,
			"fswUser"=>$fswUser,
			"actNum"=>$actNum
		 
		));
		$this->smarty->display("fsw_user/index.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$fswUser=MM("fsw","fsw_user")->get($userid);
		$fswUser=MM("fsw","fsw_user")->selectRow("userid=".$userid);
		$fswUser["true_user_head"]=images_site($fswUser["user_head"]);
		$this->smarty->goAssign(array(
			 
			"data"=>$fswUser,
		 
		 
		));
		$this->smarty->display("fsw_user/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$fswUser=MM("fsw","fsw_user")->get($userid);
		$un=array("grade","join_num");
		$data=MM("fsw","fsw_user")->postData($un);
		MM("fsw","fsw_user")->update($data,"userid=".$userid);
		$this->goAll("success");
	}
	
	
	public function onList(){
		$list=MM("fsw","fsw_user")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>" grade DESC",
			"limit"=>100
		));
		 
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
}