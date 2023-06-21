<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_img_promptControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$limit=get("limit","i");
			$limit=$limit>0?$limit:12;
			$limit=min(24,$limit);
			$where=" status=1 ";
			$url="/module.php?m=aichat_img_prompt&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" promptid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_img_prompt")->select($option,$rscount);
			$userid=M("login")->userid;
			if(!empty($data)){
				$favIds=M("fav")->selectCols(array(
					"where"=>" userid=".$userid." AND tablename='aichat_img_prompt'  ",
					"fields"=>"objectid"
				));
				foreach($data as $k=>$v){
					if(in_array($v["promptid"],$favIds)){
						$v["isfav"]=1;
					}else{
						$v["isfav"]=0;
					}
					
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
			$this->smarty->display("aichat_img_prompt/index.html");
		}
	}	
?>		