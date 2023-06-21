<?php
class hongbao_sendModel extends model{
	public $table="mod_hongbao";
	public function __construct(){
		parent::__construct();
	}
	public function send($ops){
		$userid=intval($ops["userid"]);
		$send_name=isset($ops["send_name"])?$ops["send_name"]:"守护天使";
		$wishing=isset($ops["wishing"])?$ops["wishing"]:"祝您生活愉快";
		$hbuser=MM("hongbao","hongbao_user")->get($userid);
		if($hbuser["money"]<1){
			return false;
		}
		$wx=M("weixin")->selectRow("status=1");
		include "api/wxpay/lib/WxAppPay.Config.php"; 
		WxPayConfig::init($wx);
		require ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
		$hb=new WxPayHongbao();
		$openlogin=M("openlogin")->selectRow("xfrom='weixin' AND userid=".$userid);
		if($openlogin){
			MM("hongbao","hongbao_user")->addmoney(array(
				"userid"=>$userid,
				"typeid"=>1,
				"money"=>-$hbuser['money'],
				"dateline"=>time(),
				"content"=>"官网给你发了".$hbuser['money']."元红包，现在红包账户0元。"
			));		
			$res=$hb->send(array(
				"re_openid"=>$openlogin['openid'],
				"total_amount"=>$hbuser['money']*100,
				"total_num"=>1,
				"send_name"=>$send_name,
				"wishing"=>$wishing
			));	
			M("mod_hongbao_sendlog")->insert(array(
				"userid"=>$userid,
				"money"=>$hbuser['money'],
				"dateline"=>time(),
				"status"=>$res['result_code'],
				"msg"=>$res['err_code_des'],
				"content"=>base64_encode(json_encode($res))
			));
		}
		return true;
	}
}
?>