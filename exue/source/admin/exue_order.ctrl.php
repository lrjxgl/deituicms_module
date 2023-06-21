<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
	  
			$where=" 1 ";
			$url="/module.php?m=exue_order&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$cids[]=$v["courseid"];
				}
				$cols=MM("exue","exue_course")->getListByIds($cids);
				foreach($data as $k=>$v){
					$p=$cols[$v["courseid"]];
					$p["price"]=$v["money"];
					$p["createtime"]=$v["createtime"];
					$p["orderid"]=$v["orderid"];
					 
					$data[$k]=$p;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("exue_order/index.html");
		}
		
		public function onShow(){
			 
			$orderid=get_post("orderid","i");
			$order=M("mod_exue_order")->selectRow(array("where"=>"orderid=".$orderid));
			 
			$order["status_name"]=MM("exue","exue_order")->getStatus($order["status"]);
			$course=M("mod_exue_course")->selectRow(array(
				"where"=>" courseid=".$order["courseid"],
				"fields"=>"*"
			));
			$course["imgurl"]=images_site($course["imgurl"]);
			$shop=M("mod_exue_shop")->selectRow(array(
				"where"=>" shopid=".$order["shopid"],
				"fields"=>"*"
			));
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$stList=MM("exue","exue_student")->Dselect(array(
				"where"=>" userid=".$userid." AND status=1 "
			));
			$student=array();
			if($order["stid"]){
				$student=MM("exue","exue_student")->get($order["stid"]);
			}
			$this->smarty->goassign(array(
				"order"=>$order,
				"course"=>$course,
				"shop"=>$shop,
				"stList"=>$stList,
				"student"=>$student
			));
			$this->smarty->display("exue_order/show.html");
		}
		
	 
		
		
	}
	
	

?>