<?php
class gread_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		 
		$type=get("type","h");
		switch($type){
			case "new":
				$where.=" AND status=0 ";
				break;
			case "confirm":
				$where.=" AND status=1 ";
				break;
			case "send":
				$where.=" AND status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3) ";
				break;
		}
		$start=get('per_page','i');
		$limit=48;
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"orderid DESC "
		);
		$rscount=true;
		$data=M("mod_gread_order")->select($option,$rscount);
		$statusList=MM("gread","gread_order")->statusList();
		if($data){
			$oids=array();
			foreach($data as $k=>$v){
				$oids[]=$v["orderid"];
			}
			$sql="select a.*,b.title,b.imgurl from
				".table('mod_gread_order_book')." as a 
				left join ".table('mod_gread_book')." as b 
				on a.bookid=b.bookid
				where a.orderid in("._implode($oids).")" 
			;
			$pros=M("mod_gread_book")->getAll($sql);
			foreach($pros as $p){
				$p["imgurl"]=images_site($p["imgurl"]);
				$pp[$p["orderid"]][]=$p;
			}
			foreach($data as $k=>$v){
				
				$v['pros']=$pp[$v["orderid"]];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["status_name"]=$statusList[$v["status"]];
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		 
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
		));
		$this->smarty->display("gread_order/my.html");
	}
	
	public function onOrder(){
		$shopid=post('shopid','i');
		$userid=M("login")->userid;
		
		$issend=post('issend','i');
		$telephone=post('telephone','h');
		$nickname=post('nickname','h');
		$address=post('address','h');
		//处理书
		$sql="select a.*,b.price 
				from  ".table('mod_gread_cart')." as a 
				left join  ".table('mod_gread_book')." as b
				on a.bookid=b.bookid
				where a.shopid=".$shopid." AND a.userid=".$userid." 				
		";
		$cart=M("mod_gread_cart")->getAll($sql);
		if(empty($cart)){
			$this->goAll("请选择要借的书籍",1);
		}
		$guser=MM("gread","gread_user")->get($userid);
		$money=0;
		foreach($cart as $v){
			$money+=$v['price'];
		}
		//未归还书籍
		
		
		if($money>$guser['bond']){
			$this->goAll("保证金不足",1);
		}
		
		$shop=M("mod_gread_shop")->selectRow("shopid=".$shopid);
		$payMoney=1;
		if($issend){
			
			$data['address']=$address;
			$data['telephone']=$telephone;
			$data['nickname']=$nickname;
			M("mod_gread_user")->update($data,"userid=".$userid);
			$data['issend']=1;
			$data['sendmoney']=$shop['sendmoney'];
			$payMoney+=$shop["sendmoney"];
		}
		$user=M("user")->getUser($userid,"userid,money");
		if($payMoney>$user["money"]){
			$this->goAll("余额不足",11,0,"/index.php?m=recharge");
		}
		M("user")->begin();
		MM("gread","gread_user")->update(array(
			"bond"=>$guser["bond"]-$money,
			"out_bond"=>$guser["out_bond"]+$money
		),"userid=".$userid);
		M("user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$payMoney,
			"content"=>"购买图书借阅卡，付费{$payMoney}元"
		));
		$data['shopid']=$shopid;
		$data['userid']=$userid;
		$data['createtime']=date("Y-m-d H:i:s");
		$data["paymoney"]=$payMoney;
		$orderid=M("mod_gread_order")->insert($data);
		
		$endtime=$data['createtime'];
		$insert=array();
		if($cart){
			foreach($cart as $v){
				$insert[]=array(
					"orderid"=>$orderid,
					"shopid"=>$shopid,
					"userid"=>$userid,
					"productid"=>$v['productid'],
					"bookid"=>$v['bookid'],
					"price"=>$v['price'],
					"endtime"=>$endtime
				);
			}
			M("mod_gread_order_book")->insertmore($insert);
		}
		M("mod_gread_cart")->delete("shopid=".$shopid." AND userid=".$userid);
		M("user")->commit();
		$this->goAll("下单成功");
	}
	
}
?>