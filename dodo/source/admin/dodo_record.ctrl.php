<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class dodo_recordControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=dodo_record&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_dodo_record")->select($option,$rscount);
			if(!empty($list)){
				$uids=array();
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($list as $k=>$v){
					$v["imgslist"]=array();
					if(!empty($v["imgsdata"])){
						$imarr=explode(",",$v["imgsdata"]);
						$ims=array();
						foreach($imarr as $img){
							$ims[]=images_site($img);
						}
						$v["imgslist"]=$ims;
					}
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("dodo_record/index.html");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_dodo_record")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>