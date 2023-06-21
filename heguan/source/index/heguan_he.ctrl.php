<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class heguan_heControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=heguan_he&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" heid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_heguan_he")->select($option,$rscount);
			if(!empty($data)){
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
			$this->smarty->display("heguan_he/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=heguan_he&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" heid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_heguan_he")->select($option,$rscount);
			if(!empty($data)){
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
			$this->smarty->display("heguan_he/index.html");
		}
		
		public function onShow(){
			$heid=get_post("heid","i");
			$data=M("mod_heguan_he")->selectRow(array("where"=>"heid=".$heid));
			$data["imgurl"]=images_site($data["imgurl"]);
			$imgList=parseImgsData($data["imgsdata"]);
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgList"=>$imgList
			));
			$this->smarty->display("heguan_he/show.html");
		}
		
	}

?>