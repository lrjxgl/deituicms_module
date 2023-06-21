<?php
class car_hongbaoModel extends model
{
	public $table="mod_car_hongbao";
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Dselect($option, &$rscount=false)
	{
		$data=$this->select($option, $rscount);
		if ($data) {
			$uids=array();
			foreach ($data as $v) {
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach ($data as $k=>$v) {
				$v["user"]=$us[$v["userid"]];
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
	public function addHongBao($ops,$car=array()){
		$fid=$ops["fid"];
		$userid=$ops["userid"];
		$hb=M("mod_car_hongbao")->selectRow("fid=".$fid." AND userid=".$userid);
		 
		if($hb){
			return false;
		}
		 
		$num=M("mod_car_hongbao")->selectOne(array(
			"where"=>"fid=".$fid,
			"fields"=>"count(*) as num"
		));
		$hmoney=M("mod_car_hongbao")->selectOne(array(
			"where"=>"fid=".$fid,
			"fields"=>"sum(money) as money"
		));
		if($num>=$car["hb_num"]){
			return false;
		}
		$money=$this->getMoney($car["hb_money"]-$hmoney,$car["hb_num"]-$num);
		M("mod_car_hongbao")->insert(array(
			"userid"=>$userid,
			"money"=>$money,
			"dateline"=>time(),
			"fid"=>$fid
		));
		//追加到红包应用里
		MM("hongbao","hongbao_user")->addmoney(array(
			"userid"=>$userid,
			"money"=>$money,
			"content"=>"你在同城信息完成任务，获得了".$money."元，"
		));
		MM("hongbao","hongbao_send")->send(array(
			"userid"=>$userid,
			"send_name"=>"任务完成红包",
			"wishing"=>"祝您生活愉快"
		));
	}
	
	public function getMoney($money,$total_num){
		
		if($total_num==1){
			return $money;
		}
		if($money<=0){
			return 0;
		}
		if($money==$total_num*0.01){
			return 0.01;
		}
		//确保最低金额
		$mx=0.01*$total_num;
		$money=$money-$mx;
		$min=max(1,intval(100*$money*0.01));
		$max=max(1,intval(100*$money*0.2));
		$money=rand($min,$max)/100;
		return $money;
	}
}
