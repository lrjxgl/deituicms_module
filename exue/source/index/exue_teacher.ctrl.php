<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_teacherControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=exue_teacher&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tcid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_teacher")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("exue_teacher/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=exue_teacher&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tcid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_teacher")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("exue_teacher/index.html");
		}
		
		public function onShow(){
			$tcid=get_post("tcid","i");
			$data=M("mod_exue_teacher")->selectRow(array("where"=>"tcid=".$tcid));
			$data["imgurl"]=images_site($data["imgurl"]);
			 
			$xsnum=MM("exue","exue_order")->selectOne(array(
				"where"=>" tcid=".$tcid,
				"fields"=>"count(*)"
			));
			$kcids=MM("exue","exue_course_teacher")->selectCols(array(
				"where"=>" tcid=".$tcid,
				"fields"=>"courseid"
			));
			if(!empty($kcids)){
				$kcList=MM("exue","exue_course")->Dselect(array(
					"where"=>" courseid in("._implode($kcids).") "
				));
			}
			$kcnum=count($kcids);
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"kcList"=>$kcList,
				"kcnum"=>$kcnum,
				"xsnum"=>$xsnum
			));
			$this->smarty->display("exue_teacher/show.html");
		}
		
	}

?>