<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_utagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=group_utag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_utag")->select($option,$rscount);
			
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
			$this->smarty->display("group_utag/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=group_utag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_utag")->select($option,$rscount);
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
			$this->smarty->display("group_utag/index.html");
		}
		
		public function onShow(){
			$tagid=get_post("tagid","i");
			$data=M("mod_group_utag")->selectRow(array("where"=>"tagid=".$tagid));
			
			$where=" tags like '%".$data["title"]."%' ";
			 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$res=MM("group","group_title")->select($option,$rscount);
			/*$res=M("mod_group_utag_index")->select($option,$rscount);
			 
			if(!empty($res)){
				$ids=[];
				foreach($res as $v){
					$ids[]=$v["objectid"];
				}
				$rss=MM("group","group_title")->getListByIds($ids);
				foreach($res as $k=>$v){
					if(!isset($rss[$v["objectid"]])){
						unset($res[$k]);
						continue;
					} 
					$v=array_merge($v,$rss[$v["objectid"]]);
					$res[$k]=$v;
				}
			}*/
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(array(
				"data"=>$data,
				"list"=>$res
			));
			$this->smarty->display("group_utag/show.html");
		}
		
	}

?>