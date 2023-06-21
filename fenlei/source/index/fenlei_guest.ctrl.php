<?php
class fenlei_guestControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onIndex(){
		$userid=M("login")->userid;
		$list=M("mod_fenlei_guestindex")->select(array(
			"where"=>" userid=".$userid,
			"order"=>" createtime DESC"
		));
		if($list){
			foreach($list as $v){
				$uids[]=$v["touserid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,user_head,nickname");
			
			foreach($list as $k=>$v){
				$v["to_user_head"]=$us[$v["touserid"]]["user_head"];
				$v["to_nickname"]=$us[$v["touserid"]]["nickname"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$list[$k]=$v;
			}
		}
	 
		$this->smarty->goAssign(array(
			"list"=>$list,			 
		));
		$this->smarty->display("fenlei_guest/index.html");
	}
	
	public function onUser(){
		$id=get("id","i");
		$fenlei=M("mod_fenlei")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$touserid=get("touserid","i");
		$user=M("user")->getUser($userid);
		if(!$touserid){
			$touserid=$fenlei["userid"];
		}
		if($touserid==$userid){
			$this->goAll("不能跟自己咨询",1);
		}
		$touser=M("user")->getUser($touserid);
		 
		$this->smarty->goAssign(array(
			"fenlei"=>$fenlei,
			"touserid"=>$touserid,
			"touser"=>$touser
		));
		$this->smarty->display("fenlei_guest/user.html");
	}
	//根据分类获取对话 
	 
	public function onFenlei(){
		$id=get("id","i");
		$fenlei=M("mod_fenlei")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$touserid=get("touserid","i");
		
		if(!$touserid){
			$touserid=$fenlei["userid"];
			$user=M("user")->getUser($userid);
			$touser=M("user")->getUser($fenlei["userid"]);
		}else{
			$user=M("user")->getUser($userid);
			$touser=M("user")->getUser($touserid);
		}
		
		M("mod_fenlei_guest")->update(array(
			"status"=>1
		)," userid=".$userid."  AND touserid=".$touserid." AND status=0  "); 
		$list=M("mod_fenlei_guest")->select(array(
			"where"=>"  userid=".$userid."  AND touserid=".$touserid." AND status in(0,1)  ",
			"order"=>" id DESC",
			"limit"=>96
		));
		if($list){
			foreach($list as $k=>$v){
				
				if($v["author"]==$userid){
					$v["isme"]=1;
					$v["nickname"]=$user["nickname"];
					$v["user_head"]=$user["user_head"];
				}else{
					$v["isme"]=0;
					$v["to_nickname"]=$touser["nickname"];
					$v["to_user_head"]=$touser["user_head"];
				}
				$list[$k]=$v;
			}
		}
		$per_page=0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"fenlei"=>$fenlei,
			"touser"=>$touserid,
			"per_page"=>$per_page
		));
		$this->smarty->display("fenlei_guest/fenlei.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		 
		$fenlei=M("mod_fenlei")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$touserid=get_post("touserid","i");
		if(!$touserid){
			$touserid=$fenlei["userid"];
		}
		if($touserid==$userid){
			$this->goAll("不能跟自己咨询",1);
		}
		$content=post("content","h");
		M("mod_fenlei_guest")->insert(array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"content"=>$content,
			"objectid"=>$id,
			"author"=>$userid,
			"status"=>1,
			"createtime"=>date("Y-m-d H:i:s")
		));
		M("mod_fenlei_guest")->insert(array(
			"userid"=>$touserid,
			"touserid"=>$userid,
			"content"=>$content,
			"author"=>$userid,
			"objectid"=>$id,
			"status"=>0,
			"createtime"=>date("Y-m-d H:i:s")
		));
		//索引
		$row=M("mod_fenlei_guestindex")->selectRow("userid=".$userid." AND objectid=".$id);
		if($row){
			M("mod_fenlei_guestindex")->update(array(
				"userid"=>$userid,
				"touserid"=>$touserid,
				"content"=>$content,
				"objectid"=>$id,
				"createtime"=>date("Y-m-d H:i:s")
			),"id=".$row["id"]);
		}else{
			M("mod_fenlei_guestindex")->insert(array(
				"userid"=>$userid,
				"touserid"=>$touserid,
				"content"=>$content,
				"objectid"=>$id,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		//商家
		$row=M("mod_fenlei_guestindex")->selectRow("touserid=".$userid." AND objectid=".$id);
		if($row){
			M("mod_fenlei_guestindex")->update(array(
				"userid"=>$touserid,
				"touserid"=>$userid,
				"content"=>$content,
				"objectid"=>$id,
				"createtime"=>date("Y-m-d H:i:s")
			),"id=".$row["id"]);
		}else{
			M("mod_fenlei_guestindex")->insert(array(
				"userid"=>$touserid,
				"touserid"=>$userid,
				"content"=>$content,
				"objectid"=>$id,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		
		$this->goAll("保存成功");
	}
	
	public function oncheckNew(){
		 
		$userid=get_post("userid","i");
		if(!$userid){
			$userid=M("login")->userid; 
		} 
		$ctime=date("Y-m-d H:i:s",time()-36000);
		 
		$num=M("mod_fenlei_guest")->selectOne(array(
			"where"=>"  userid=".$userid." AND status=0 AND createtime>'".$ctime."' ",
			"fields"=>" count(*) as ct"
		));
		if($num){
			echo json_encode(array(
				"message"=>"success",
				"error"=>0,
				"num"=>$num
			));
		}else{
			echo json_encode(array(
				"message"=>"error",
				"error"=>1,
				"num"=>0
			));
		}
	}
	
}
?>