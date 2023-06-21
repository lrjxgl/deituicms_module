<?php
class im_giftControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$list=M("mod_im_gift")->select($ops);
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("im_gift/index.html");
	}
	public function onSend(){
		M("login")->checkLogin();
		$giftid=get("giftid","i");
		$touserid=get("touserid","i");
		$userid=M("login")->userid;
		$content=post("content","h");
		$row=M("mod_im_gift")->selectRow("giftid=".$giftid);
		$user=M("user")->getUser($userid,"userid,gold");
		if($row["price"]>$user["gold"]){
			$this->goAll("金币不足，请先充值",1);
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"gold"=>-$row["price"],
			"content"=>"您购买".$row["title"]."花了".$row["price"]."个金币"
		));
		$sendid=M("mod_im_gift_send")->insert(array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"giftid"=>$giftid,
			"content"=>$content,
			"dateline"=>time()
		));
		$rdata=array(
			"sendid"=>$sendid
		);
		$this->goAll("发送成功",0,$rdata);
		
	}
	
	public function onAccept(){
		M("login")->checkLogin();
		$sendid=get("sendid","i");
		$send=M("mod_im_gift_send")->selectRow("sendid=".$sendid);
		if(empty($send)){
			$this->goAll("出错了",1);
		}
		$giftid=$send["giftid"];
		$gift=M("mod_im_gift")->selectRow("giftid=".$giftid);
		$gift["imgurl"]=images_site($gift["imgurl"]);
		$this->smarty->goAssign(array(
			"gift"=>$gift
		));
		
	}
	
}
?>