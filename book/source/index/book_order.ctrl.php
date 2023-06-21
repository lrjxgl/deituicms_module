<?php
class book_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onOrder(){
		M("login")->checkLogin();
		M("user_vip")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->selectRow("userid=".$userid);
		$bookid=get_post("bookid",'i');
		$order=M("mod_book_order")->selectRow("userid=".$userid." AND isdelete=0 AND bookid=".$bookid);
		if($order){
			$this->goAll("已经购买了");
		}
		$book=M("mod_book")->selectRow("bookid=".$bookid);
		if($book['ispay']==0){
			$this->goAll("免费书籍无需购买",1);
		}
		
		/*金币购买方法
		if($user['gold']<$book['gold']){
			$rdata=array(
				"action"=>"buygold"
			);
			$this->goAll("金币不足，无法购买",1,$rdata);
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"gold"=>-$book['gold'],
			"content"=>"购买书籍花了[money]个金币，"
		));
		*/
	   $backurl="/module.php?m=book&a=show&bookid=".$bookid;
	   if($user['money']>=$book['money'] && 1==2){
		   M("user")->addMoney(array(
		   	"userid"=>$userid,
		   	"money"=>-$book['money'],
		   	"content"=>"购买书籍花了￥[money]元，"
		   ));
		   M("mod_book_order")->insert(array(
		   	"bookid"=>$bookid,
		   	"userid"=>$userid,
		   	"createtime"=>date("Y-m-d H:i:s")
		   ));
		   $redata=array(
		   	"action"=>"success"
		   );
		   $this->goALl("购买成功",0,$redata);
	   }else{
		   $orderno="Re".M("maxid")->get();
		   $orderdata=array(
		   	"table"=>"plugin",
		   	"callback"=>'
		   		M("mod_book_order")->add(array(
					"bookid"=>'.$bookid.',
					"userid"=>'.$userid.'
		   		));
		   	',
		   	"url"=>$backurl
		   );
		   $orderdata=base64_encode(json_encode($orderdata)); 
		   $orderinfo=$order_product="购买教程".$book["title"];
		   $pay_type=INWEIXIN?"wxpay":"alipay";
		   $fromapp=get("fromapp");
		   $money= $book['money'];
		   $openid=get('openid','h'); 
		   $rechargeid=M("recharge")->insert(array(
		   	"orderno"=>$orderno,
		   	"userid"=>$userid,
		   	"money"=>$money,
		   	"pay_type"=>$pay_type,
		   	"dateline"=>time(),
		   	"openid"=>$openid,
		   	"orderinfo"=>$orderinfo, 
		   	"orderdata"=>$orderdata,
		   	"status"=>2,
		   ));
		   $bank_type="";
		   
		   $url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
		   $url.="&orderno=$orderno";
		   $url.="&bank_type=".$bank_type;
		   $url.="&order_product=".urlencode($order_product);
		   $url.="&order_price=".$money;
		   $url.="&order_info=".urlencode($order_info);
		   $url.="&backurl=".urlencode($backurl);
		   $redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
		   );
	   }
		
		$this->goALl("正在前往支付",0,$redata);
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" isdelete=0 AND userid=".$userid;
		$option=array(
			"where"=>$where,
			"limit"=>24
		);
		$rscount=true;
		$data=MM("book","book_order")->Dselect($option,$rscount);
	 
		$this->smarty->goAssign(array(
			"list"=>$data
		));
		$this->smarty->display("book_order/my.html");
	}
}
?>