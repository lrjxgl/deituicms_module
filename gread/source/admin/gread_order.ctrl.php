<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$start=get('per_page','i');
			$limit=48;
			$type=get("type","h");
			switch($type){
				case "new":
					$where="  status=0 ";
					break;
				case "confirm":
					$where="status=1 ";
					break;
				case "send":
					$where=" status=2 ";
					break;
				case "finish":
					$where=" status=3 ";
					break;
				default:
					$where=" status in(0,1,2,3) ";
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
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page; 
			$this->smarty->goAssign(array(
				"list"=>$list,
				"per_page"=>$per_page,
				"rscount"=>$rscount
			));
			$this->smarty->display("gread_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			if($orderid){
				$data=M("mod_gread_order")->selectRow(array("where"=>"orderid={$orderid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_gread_order")->update(array("bstatus"=>$bstatus),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_gread_order")->update(array("bstatus"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>