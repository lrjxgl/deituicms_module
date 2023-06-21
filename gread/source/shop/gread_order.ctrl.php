<?php
class gread_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="shopid=".SHOPID;
		$start=get('per_page','i');
		$limit=48;
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
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"orderid DESC "
		);
		$rscount=true;
		$list=M("mod_gread_order")->select($option,$rscount);
		$statusList=MM("gread","gread_order")->statusList();
		if($list){
			foreach($list as $k=>$v){
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["status_name"]=$statuslist[$v["status"]];
				$list[$k]=$v;
			}
		}
		if($list){
			foreach($list as $k=>$v){
				$sql="select a.bookid,a.price,b.title,b.imgurl from 
					".table('mod_gread_order_book')." as a 
					left join ".table('mod_gread_book')." as b 
					on a.bookid=b.bookid
					where a.orderid=".$v['orderid'] 
				;
				$pros=M("mod_gread_book")->getAll($sql);
				foreach($pros as $pk=>$p){
					$p["imgurl"]=images_site($p["imgurl"]);
					$pros[$pk]=$p;
				}
				$v['pros']=$pros;
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		} 
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("gread_order/index.html");
	}
	
	public function onFinish(){
		$orderid=get("orderid","i");
		$order=M("mod_gread_order")->selectRow("orderid=".$orderid);
		if($order["status"]>=3){
			$this->goAll("订单已经处理过了",1);
		}
		M("mod_gread_order")->update(array(
			"status"=>3
		),"orderid=".$orderid);
		M("mod_gread_order_book")->update(array(
			"isback"=>0,
			"status"=>1,
			"starttime"=>date("Y-m-d H:i:s")
		),"orderid=".$orderid);
		//处理图书
		$blist=M("mod_gread_order_book")->select(array(
			"where"=>" orderid=".$orderid
		));
		$proids=array();
		foreach($blist as $v){
			$proids[]=$v["productid"];
		}
		M("mod_gread_shop_product")->changenum("sold_num",1," id in("._implode($proids).") ");
		//计算费用
		$money=$order["paymoney"]*0.8;
		MM("gread","gread_shop_money")->addMoney(array(
			"shopid"=>SHOPID,
			"income"=>$money,
			"balance"=>$money,
			"content"=>"图书借阅订单完成,获得{$money}元"
		));
		$this->goAll("success");
	}
	
	public function onCancel(){
		$orderid=get("orderid","i");
		$order=M("mod_gread_order")->selectRow("orderid=".$orderid);
		if($order["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($order["status"]>=3){
			$this->goAll("已处理",1);
		}
		$guser=M("mod_gread_user")->selectRow("userid=".$order["userid"]);
		M("mod_gread_order")->update(array(
			"status"=>4
		),"orderid=".$orderid);
		
		$blist=M("mod_gread_order_book")->select(array(
			"where"=>" orderid=".$orderid
		));
		$proids=array();
		$book_money=0;
		foreach($blist as $v){
			$proids[]=$v["productid"];
			$book_money+=$v["price"];
		}
		//书籍库存调整
		M("mod_gread_order_book")->update(array(
			"status"=>4,
			"isback"=>4
		),"orderid=".$orderid);
		M("mod_gread_order_book")->query("
			update ".table('mod_gread_shop_product')." 
			set free_num=free_num+1,out_num=out_num-1
			where id in("._implode($proids).")
		");
		//取消保证金占用
		M("mod_gread_user")->update(array(
			"bond"=>$guser["bond"]+$book_money,
			"out_bond"=>max(0,$guser["out_bond"]-$book_money)
		),"userid=".$order["userid"]);
		$this->goAll("取消成功");
	}
	
}
?>