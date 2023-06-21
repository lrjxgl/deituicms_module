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
			$where=" shopid=".SHOPID." AND status in(0,1,2)";
			$url="/moduleshop.php?m=exue_order&a=default";
			$limit=24;
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
					$v["title"]=$p["title"];
					$v["imgurl"]=$p["imgurl"];
					 
					 
					$data[$k]=$v;
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
			 
			$student=array();
			if($order["stid"]){
				$student=MM("exue","exue_student")->get($order["stid"]);
			}
			$tcList=MM("exue","exue_teacher")->Dselect(array(
				"where"=>" shopid=".SHOPID." AND status=1 "
			));
			$teacher=array();
			if($order["tcid"]){
				$teacher=MM("exue","exue_teacher")->get($order["tcid"]);
			}
			$this->smarty->goassign(array(
				"order"=>$order,
				"course"=>$course,
				"shop"=>$shop,
				"tcList"=>$tcList, 
				"student"=>$student,
				"teacher"=>$teacher
			));
			$this->smarty->display("exue_order/show.html");
		}
		
		public function onbindTeacher(){
			 
			$orderid=post("orderid","i");
			$tcid=post("tcid","i");
			$order=M("mod_exue_order")->selectRow("orderid=".$orderid);
			if($order["tcid"]){
				$this->goAll("该课程已绑定教师了",1);
			}
			 
			M("mod_exue_order")->update(array(
				"tcid"=>$tcid
			),"orderid=".$orderid);
			$this->goAll("保存成功");
		}
		
	}

?>