<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_tags_indexControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$tagid=get("tagid","i");
			$gkey=get("gkey","h");
			if($gkey){
				$group=M("mod_house_tags")->selectRow("gkey='".$gkey."'");
				$tagid=$group["tagid"];
			}else{
				$group=M("mod_house_tags")->selectRow("tagid=".$tagid);
			}
			if(!$tagid){
				$this->goAll("参数出错了",1);
			}
			$where=" tagid=".$tagid;
			$url="/module.php?m=house_tags_index&tagid=".$tagid;
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC,id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_tags_index")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$proids[]=$v["objectid"];
				}
				$pros=MM("house","house_resource")->getListByIds($proids);
				foreach($data as $k=>$v){
					$v=$pros[$v["objectid"]];
					
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
					"tags"=>$group,
					"url"=>$url
				)
			);
			$this->smarty->display("house_tags_index/index.html");
		}
		
		 
		
		
	}

?>