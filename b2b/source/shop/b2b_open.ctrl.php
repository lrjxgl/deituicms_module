<?php
class b2b_openControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onTest(){
		$orderid=get("orderid","i");
		MM("b2b","b2b_shop_notice")->sendNewOrder($orderid);
		
		echo "发送成功";
	}
	public function onDefault(){
		$wexin=M("mod_b2b_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin'");
		$this->smarty->goAssign(array(
			"weixin"=>$weixin
		));
		$this->smarty->display("b2b_open/index.html");
	}
	public function onGoWeixin(){
		$wx=M("weixin")->selectRow(array("where"=>"","order"=>"id DESC"));
		$redirect_uri=HTTP_HOST."/moduleshop.php?m=b2b_open&a=BindWeixin&wid=".$wx['id'];
		$url=" https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wx['appid']."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
		header("Location: $url");
		exit();
	}
	public function onBindWeixin(){
		$wx=M("weixin")->selectRow(array("where"=>"","order"=>"id DESC"));
		$c=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$wx['appid']."&secret=".$wx['appkey']."&code=".$_GET['code']."&grant_type=authorization_code");
		$data=json_decode($c,true);
		if(isset($data['access_token']) && !empty($data['openid'])){
			$row=M("mod_b2b_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin'");
			$content=arr2str(array(
				"openid"=>$data["openid"],
				"access_token"=>$data["access_token"]
			));
			if($row){
				M("mod_b2b_shop_openbind")->update(array(
					"content"=>$content
				),"id=".$row["id"]);
			}else{
				M("mod_b2b_shop_openbind")->insert(array(
					"shopid"=>SHOPID,
					"k"=>"weixin",
					"content"=>$content
				));
			}
			$this->goAll("绑定成功");
		}else{
			$this->goAll("绑定失败");
		}
	}
	
}