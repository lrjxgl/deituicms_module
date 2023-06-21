<?php 
class im_matchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$type=get("type","h");
		$stype="yuan";
		if($type=="yuan"){
			$stype="yuan";
		}elseif($type=="music"){
			$stype="music";
		}
		//获取已经匹配成功的
		$etime=time()-60;
		$success=M("mod_im_match_success")->selectRow("userid=".$userid." AND dateline>".$etime." AND stype='".$stype."' ");
		if($success){
			M("mod_im_match_success")->delete("id=".$success["id"]);
			$user=M("user")->getUser($success["touserid"],"userid,nickname,user_head");
			$user["yuanfen"]=rand(85,100);
			$this->goAll("success",0,array(
				"user"=>$user
			));
		}
		$me=M("mod_im_match")->selectRow("userid=".$userid." AND stype='".$stype."' ");
		if(!$me || time()-$me["dateline"]>60){
			$user=M("user")->selectRow("userid=".$userid);
			$age=intval((time()-strtotime($user["birthday"]))/(3600*24*365));
			if($me){
				M("mod_im_match")->update(array(
					"userid"=>$userid,
					"gender"=>$me["gender"],
					"stype"=>$stype,
					"age"=>$age
				),"userid=".$userid);
			}else{
				M("mod_im_match")->insert(array(
					"userid"=>$userid,
					"gender"=>$me["gender"],
					"stype"=>$stype,
					"age"=>$age
				));
			}
			
			$me=M("mod_im_match")->selectRow("userid=".$userid);
		}
		//匹配
		$gender=0;
		if($me["gender"]==1){
			$gender=2;
		}elseif($me["gender"]==2){
			$gender=1;
		}
		$sage=$me["age"]-6;
		$eage=$me["age"]+6;
		$u=M("mod_im_match")->selectRow(array(
			"where"=>" gender=".$gender." AND age>".$sage." AND age<".$eage." AND userid!=".$userid." ",
			"order"=>"id DESC"
		));
		if($u){
			M("mod_im_match")->delete("userid=".$u["userid"]);
			M("mod_im_match")->delete("userid=".$userid);
			$user=M("user")->getUser($u["userid"],"userid,nickname,user_head");
			$user["yuanfen"]=rand(85,100);
			//扣费
			M("mod_im_match_success")->insert(array(
				"userid"=>$userid,
				"touserid"=>$u["userid"],
				"stype"=>$stype,
				"dateline"=>time()
			));
			M("mod_im_match_success")->insert(array(
				"touserid"=>$userid,
				"userid"=>$u["userid"],
				"stype"=>$stype,
				"dateline"=>time()
			));
			$this->goAll("success",0,array(
				"user"=>$user
			));
		}else{
			$this->goAll("error",1);
		}
		
		
	}
	
	public function onClear(){
		$stime=time()-60;
		M("mod_im_match")->selectRow("dateline<".$stime);
		$this->goAll("success",0);
	}
	
}