<?php
class freeshop_invite_logControl extends skymvc{
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function onInit(){
		M("login")->checkLogin();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$where="   userid=".$userid;
		$start=get('per_page','i');
		$limit=20;
		$url="/module.php?m=freeshop_invite_log";
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
		);
		$rscount=true;
		$data=M("mod_freeshop_invite_log")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago($v['dateline']);
				
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$rscount>$per_page?$per_page:0;
		$account=MM("freeshop","freeshop_invite_account")->get($userid);
		$this->smarty->goassign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"account"=>$account
			
		));
		
		$this->smarty->display("freeshop_invite_log/index.html");
	}
	
	public function onSend(){
		$userid=M("login")->userid;
		//追加到红包应用里
		$wx=M("weixin")->selectRow("status=1");
		include "api/wxpay/lib/WxAppPay.Config.php"; 
		WxPayConfig::init($wx);
		require ROOT_PATH."api/wxpay/lib/WxPayHongbao.php";
		$hb=new WxPayHongbao();
		$openlogin=M("openlogin")->selectRow("xfrom='weixin' AND userid=".$userid);
		$account=MM("freeshop","freeshop_invite_account")->get($userid);
		if($account["money"]<1){
			$this->goAll("满1元才能发红包",1);
		}
		if($openlogin){
			MM("freeshop","freeshop_invite_account")->add(array(
				"userid"=>$userid,
				"money"=>-$account["money"],
				"content"=>"红包提现"
			));
			$send_name="福鼎生活网";
			$wishing="邀请奖励红包提现";	
			$res=$hb->send(array(
				"re_openid"=>$openlogin['openid'],
				"total_amount"=>$account['money']*100,
				"total_num"=>1,
				"send_name"=>$send_name,
				"wishing"=>$wishing
			));	
			M("mod_hongbao_sendlog")->insert(array(
				"userid"=>$userid,
				"money"=>$account['money'],
				"dateline"=>time(),
				"status"=>$res['result_code'],
				"msg"=>$res['err_code_des'],
				"content"=>base64_encode(json_encode($res))
			));
			$this->goAll("红包提现成功");
		}else{
			$this->goAll("您还未绑定微信公众号",1);
		}
	}
	
}
?>