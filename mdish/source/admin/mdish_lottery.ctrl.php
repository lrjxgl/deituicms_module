<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mdish_lotteryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$sday=get("sday","i");
			$where=" status in(0,1,2) ";
			$url="moduleadmin.php?m=mdish_lottery";
			if($sday){
				$where.=" AND sday=".$sday;
				$url.="&sday=".$sday;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ltid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_lottery")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"sday"=>$sday
				)
			);
			$this->smarty->display("mdish_lottery/index.html");
		}
		
		public function onAdd(){
			$ltid=get_post("ltid","i");
			if($ltid){
				$data=M("mod_mdish_lottery")->selectRow(array("where"=>"ltid=".$ltid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mdish_lottery/add.html");
		}
		
		public function onSave(){
			$ltid=get_post("ltid","i");
			$data=M("mod_mdish_lottery")->postData();
			if($ltid){
				M("mod_mdish_lottery")->update($data,"ltid='$ltid'");
			}else{
				M("mod_mdish_lottery")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$ltid=get_post('ltid',"i");
			$status=get_post("status","i");
			M("mod_mdish_lottery")->update(array("status"=>$status),"ltid=$ltid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$ltid=get_post('ltid',"i");
			M("mod_mdish_lottery")->update(array("status"=>11),"ltid=$ltid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>