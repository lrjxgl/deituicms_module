<?php
	class gread_backorderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onDefault(){
			$where=" shopid=".SHOPID;
			$url="/moduleshop.php?m=gread_backorder";
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
				case "cancel":
					$where.=" AND status=4 ";
					break;	
				default:
					$where.=" AND status in(0,1,2,3,4) ";
					break;
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
			 
			$this->smarty->display("gread_backorder/index.html");
		}
		
		public function onFinish(){
			$orderid=get("orderid","i");
			$order=M("mod_gread_backorder")->selectRow("orderid=".$orderid);
			if($order["status"]>=3){
				$this->goAll("订单已经处理过了",1);
			}
			$guser=M("mod_gread_user")->selectRow("userid=".$order["userid"]);
			
			M("mod_gread_backorder")->begin();
			M("mod_gread_user")->update(array(
				"bond"=>$guser["bond"]+$order["book_money"],
				"out_bond"=>max(0,$guser["out_bond"]-$order["book_money"])
			),"userid=".$order["userid"]);
			M("mod_gread_backorder")->update(array(
				"status"=>3
			),"orderid=".$orderid);
			M("mod_gread_backorder_book")->update(array(
				"isback"=>1,
				"status"=>3,
				"backtime"=>date("Y-m-d H:i:s")
			),"orderid=".$orderid);
			//处理图书
			$blist=M("mod_gread_backorder_book")->select(array(
				"where"=>" orderid=".$orderid
			));
			$proids=array();
			$order_bookids=[];
			foreach($blist as $v){
				$proids[]=$v["productid"];
				$order_bookids[]=$v["order_bookid"];
			}
			M("mod_gread_order_book")->update(array(
				"isback"=>1
			),"id in("._implode($order_bookids).") ");
			M("mod_gread_shop_product")->query("
				update ".table('mod_gread_shop_product')." 
				set free_num=free_num+1,out_num=out_num-1
				where id in("._implode($proids).")
			");
			M("mod_gread_backorder")->commit();
			//计算费用
			$this->goAll("success");
		}
		
		public function onCancel(){
			$orderid=get("orderid","i");
			$order=M("mod_gread_backorder")->selectRow("orderid=".$orderid);
			if($order["status"]>=3){
				$this->goAll("订单已经处理过了",1);
			}
			
			
			M("mod_gread_backorder")->begin();
		 
			M("mod_gread_backorder")->update(array(
				"status"=>4
			),"orderid=".$orderid);
			 
			//处理图书
			$blist=M("mod_gread_backorder_book")->select(array(
				"where"=>" orderid=".$orderid,
				"fields"=>"orderid,order_bookid"
			));
			$proids=array();
			$order_bookids=[];
			foreach($blist as $v){
			
				$order_bookids[]=$v["order_bookid"];
			}
			//还原待还图书
			M("mod_gread_order_book")->update(array(
				"status"=>1
			),"id in("._implode($order_bookids).") ");
			M("mod_gread_backorder")->commit();
			//计算费用
			$this->goAll("success");
		}
		
	}
?>