<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class tutor_lessonControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=tutor_lesson&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" lessonid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_tutor_lesson")->select($option,$rscount);
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
			$this->smarty->display("tutor_lesson/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/moduleadmin.php?m=tutor_lesson&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" lessonid DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=MM("tutor","tutor_lesson")->Dselect($option,$rscount);
			if($list){
				foreach($list as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("tutor","tutor_shop")->getListByIds($shopids);
				foreach($list as $k=>$v){
					$v["shop"]=$sps[$v["shopid"]];
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("tutor_lesson/index.html");
		}
		
		public function onShow(){
			$lessonid=get_post("lessonid","i");
			$lesson=M("mod_tutor_lesson")->selectRow(array("where"=>"lessonid=".$lessonid));
			$shop=MM("tutor","tutor_shop")->get($lesson["shopid"]);
			$userid=M("login")->userid;
			$addr=M("user_lastaddr")->selectRow("userid=".$userid);
			$this->smarty->goassign(array(
				"lesson"=>$lesson,
				"shop"=>$shop,
				"addr"=>$addr
			));
			$this->smarty->display("tutor_lesson/show.html");
		}
		
	}

?>