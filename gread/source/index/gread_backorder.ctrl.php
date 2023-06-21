<?php
class gread_backorderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=gread_backorder";
		$start=get('per_page','i');
		$limit=48;
		$status=get('status','i');
		if($status){
			$where.=" AND status=".$status;
			$url.="&status=".$status;
		}
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"orderid DESC "
		);
		$rscount=true;
		$data=M("mod_gread_backorder")->select($option,$rscount);
		$statusList=MM("gread","gread_order")->statusList();
		if($data){
			$oids=array();
			foreach($data as $k=>$v){
				$oids[]=$v["orderid"];
			}
			$sql="select a.*,b.title,b.imgurl from
				".table('mod_gread_backorder_book')." as a 
				left join ".table('mod_gread_book')." as b 
				on a.bookid=b.bookid
				where a.orderid in("._implode($oids).") " 
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
			"per_page"=>$per_page
		));
		$this->smarty->display("gread_backorder/my.html");
	}
	
	public function onOrder(){
		$shopid=post('shopid','i');
		if(!$shopid){
			$this->goAll("请选择店铺",1);
		}
		$userid=M("login")->userid;
		
		$issend=post('issend','i');
		$telephone=post('telephone','h');
		$nickname=post('nickname','h');
		$address=post('address','h');
		//处理书
		$sql="select a.*,b.price 
				from  ".table('mod_gread_backcart')." as a 
				left join  ".table('mod_gread_book')." as b
				on a.bookid=b.bookid
				where a.shopid=".$shopid." AND a.userid=".$userid." 				
		";
		$cart=M("mod_gread_backcart")->getAll($sql);
		if(empty($cart)){
			$this->goAll("请选择要还的书籍",1);
		}
		$book_money=0;
		foreach($cart as $v){
			$book_money+=$v['price'];
		}
		$shop=M("mod_gread_shop")->selectRow("shopid=".$shopid);
		if($issend){	
			$data['address']=$address;
			$data['telephone']=$telephone;
			$data['nickname']=$nickname;
			M("mod_gread_user")->update($data,"userid=".$userid);
			$data['issend']=1;
			$data['sendmoney']=$shop['sendmoney'];
		}
		$data["book_money"]=$book_money;
		$data['shopid']=$shopid;
		$data['userid']=$userid;
		$data['createtime']=date("Y-m-d H:i:s");
		$orderid=M("mod_gread_backorder")->insert($data);

		$endtime=$data['createtime'];
		$insert=array();
		if($cart){
			foreach($cart as $v){
				//更新借书状态
				M("mod_gread_order_book")->update(array(
					"status"=>2
				),"id=".$v['order_bookid']);
				$insert[]=array(
					"orderid"=>$orderid,
					"shopid"=>$shopid,
					"userid"=>$userid,
					"bookid"=>$v['bookid'],
					"price"=>$v['price'],
					"endtime"=>$endtime,
					"productid"=>$v['productid'],
					"order_bookid"=>$v["order_bookid"]
				);
			}
			M("mod_gread_backorder_book")->insertmore($insert);
		}
		M("mod_gread_backcart")->delete("shopid=".$shopid." AND userid=".$userid);
		$this->goAll("下单成功");
	}
	
}
?>