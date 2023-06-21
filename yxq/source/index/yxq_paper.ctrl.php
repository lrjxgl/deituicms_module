<?php
class yxq_paperControl extends skymvc{
	
	public function onDefault(){
		$this->smarty->display("yxq_paper/index.html");
	}
	
	public function onAdd(){
		$xzList=MM("yxq","yxq_paper")->xzList();
		$this->smarty->goAssign(array(
			"xzList"=>$xzList
		));
		$this->smarty->display("yxq_paper/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$wxhao=post("wxhao","h");
		$xingzuo=post("xingzuo","h");
		$description=post("description","h");
		$imgsdata=post("imgsdata","x");
		$gender=post("gender","i");
		$time=date("Y-m-d H:i:s");
		//支付
		$config=MM("yxq","yxq_config")->get();
		$money=$config["post_money"];
		$user=M("user")->selectRow("userid=".$userid);
		if($user["money"]<$money){
			$this->goAll("余额不足,请先充值",1);
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$money,
			"content"=>"发布纸条花了".$money."元"
		));
		//邀请人奖励
		if($user["invite_userid"]){
			M("invite_account")->add(array(
				"cuserid"=>$userid,
				"money"=>$money,
				"per"=>$config["invite_per"],
				"content"=>"好友消费奖励佣金[money]元"
			));
		}
		M("mod_yxq_paper")->insert(array(
			"userid"=>$userid,
			"wxhao"=>$wxhao,
			"xingzuo"=>$xingzuo,
			"description"=>$description,
			"gender"=>$gender,
			"imgsdata"=>$imgsdata,
			"createtime"=>$time,
			"updatetime"=>$time
			
		));
		$this->goAll("success");
		
	}
	
	public function onGet(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" isfinish=0 AND ispay=0  AND status in(0,1) ";
		$gender=get("gender","i");
		$where.=" AND gender=".$gender;
		$type=get("type","i");
		switch($type){
			case 1:
				break;
		}
		$paper=M("mod_yxq_paper")->selectRow(array(
			"where"=>$where,
			"order"=>"updatetime ASC"
		));
		if(empty($paper)){
			$this->goAll("暂无匹配",1);
		}
		//支付
		$config=MM("yxq","yxq_config")->get();
		$money=$config["get_money"];
		$user=M("user")->selectRow("userid=".$userid);
		if($user["money"]<$money){
			$this->goAll("余额不足,请先充值",1);
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$money,
			"content"=>"获取纸条花了".$money."元"
		));
		//邀请人奖励
		if($user["invite_userid"]){
			M("invite_account")->add(array(
				"cuserid"=>$userid,
				"money"=>$money,
				"per"=>$config["invite_per"],
				"content"=>"好友消费奖励佣金[money]元"
			));
		}
		M("mod_yxq_paper")->update(array(
			"updatetime"=>date("Y-m-d H:i:s")
		),"id=".$paper["id"]);
		$imgList=[];
		$e=explode(",",$paper["imgsdata"]);
		foreach($e as $v){
			$imgList[]=images_site($v);
		}
		$paper["imgList"]=$imgList;
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"paper"=>$paper
			)
		));
	}
	
	public function onMy(){
		
		$this->smarty->display("yxq_paper/my.html");
	}
	
	public function onMyApi(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$list=M("mod_yxq_paper")->select(array(
			"where"=>$where
		));
		if(!empty($list)){
			foreach($list as $k=>$v){
				$imgList=[];
				$e=explode(",",$v["imgsdata"]);
				foreach($e as $img){
					$imgList[]=images_site($img);
				}
				$v["imgList"]=$imgList;
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
	public function onUserApi(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$ppids=M("mod_yxq_paper_user")->selectCols(array(
			"where"=>$where,
			"fields"=>"ppid",
			"order"=>"id DESC"
		));
		if(empty($ppids)){
			$this->smarty->goAssign(array(
				"list"=>[]
			));
		}
		$where=" id in("._implode($ppids).") ";
		$list=M("mod_yxq_paper")->select(array(
			"where"=>$where
		));
		 
		if(!empty($list)){
			foreach($list as $k=>$v){
				$imgList=[];
				$e=explode(",",$v["imgsdata"]);
				foreach($e as $img){
					$imgList[]=images_site($img);
				}
				$v["imgList"]=$imgList;
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	
}