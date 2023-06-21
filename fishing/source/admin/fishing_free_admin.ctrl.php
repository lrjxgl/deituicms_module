<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_adminControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			$url="/moduleadmin.php?m=fishing_free_admin&a=default&type=".$type;
			
			switch($type){
				case "all":
					$where=" status in(0,1,2)";
					break;
				case "pass":
					$where=" status=1 ";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_admin")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as $v){
					$pids[]=$v["placeid"];
				}
				$places=MM("fishing","fishing_free_place")->getListByIds($pids,"placeid,title");
				foreach($data as $k=>$v){
					$v["place"]=$places[$v["placeid"]];
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
					"type"=>$type
				)
			);
			$this->smarty->display("fishing_free_admin/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fishing_free_admin")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_free_admin/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fishing_free_admin")->postData();
			if($id){
				M("mod_fishing_free_admin")->update($data,"id=".$id);
			}else{
				M("mod_fishing_free_admin")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$row=M("mod_fishing_free_admin")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAll("已经审核过了",1);
			}
			M("mod_fishing_free_place")->begin(); 
			M("mod_fishing_free_admin")->update(array("status"=>1),"id=".$id);
			M("mod_fishing_free_place")->update(array(
				"userid"=>$row["userid"]
			),"placeid=".$row["placeid"]);
			M("mod_fishing_free_place")->commit();
			$this->goall("审核成功",0,$status);
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			M("mod_fishing_free_admin")->update(array("status"=>2),"id=".$id);
			$this->goAll("审核成功");
			 
		}
		
		
	}

?>