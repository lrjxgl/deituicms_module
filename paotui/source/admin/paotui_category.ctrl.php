<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotui_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$typeid=get("typeid","i");
			$where=" status in(0,1,2) ";
			if($typeid){
				$where.=" AND typeid=".$typeid;
			}
			$url="/moduleadmin.php?m=paotui_category&a=default";
			$limit=20;
			$start=get("per_page","i");
			$typeList=MM("paotui","paotui")->typeList();
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" catid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui_category")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["typeid_name"]=$typeList[$v["typeid"]]["title"];
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
					"url"=>$url,
					"typeList"=>$typeList
				)
			);
			$this->smarty->display("paotui_category/index.html");
		}
		
		public function onAdd(){
			$catid=get_post("catid","i");
			$typeList=MM("paotui","paotui")->typeList();
			if($catid){
				$data=M("mod_paotui_category")->selectRow(array("where"=>"catid=".$catid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"typeList"=>$typeList
			));
			$this->smarty->display("paotui_category/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			$data=M("mod_paotui_category")->postData();
			if($catid){
				M("mod_paotui_category")->update($data,"catid='$catid'");
			}else{
				M("mod_paotui_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$status=get_post("status","i");
			M("mod_paotui_category")->update(array("status"=>$status),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			M("mod_paotui_category")->update(array("status"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>