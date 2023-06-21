<?php
class lltuan_orderControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onOrder(){
		//M("login")->checkLogin();
		$userid=M("login")->userid;
		$gid=post("gid","i");
		$group=MM("lltuan","lltuan_group")->selectRow("gid=".$gid);
		if($group["status"]!=1 || $group["group_status"]!=1){
			$this->goAll("当前拼团已结束",1);
		}
		$data=MM("lltuan","lltuan_order")->postData();
		checkEmpty($data["nickname"],"请填写联系人");
		checkEmpty($data["telephone"],"请填写电话");
		checkEmpty($data["address"],"请填写地址");
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		MM("lltuan","lltuan_order")->insert($data);
		//MM("lltuan","lltuan_group")->changenum("join_num",$data["num"],"gid=".$data["gid"]);
		$this->goAll("下单成功");
	}
}