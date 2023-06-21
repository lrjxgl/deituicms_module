<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_driverControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pinche_driver&a=default";
			$truename=get("truename","h");
			$telephone=get("telephone","h");
			if($truename){
				$where.=" AND truename='".$truename."' ";
			}
			if($telephone){
				$where.=" AND telephone='".$telephone."' ";
			}
			$carno=get("carno","h");
			if($carno){
				$where.=" AND carno='".$carno."' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" driverid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_driver")->select($option,$rscount);
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
					"truename"=>$truename,
					"telephone"=>$telephone,
					"carno"=>$carno
				)
			);
			$this->smarty->display("pinche_driver/index.html");
		}
		public function onShow(){
			$driverid=get_post("driverid","i");
			$data=M("mod_pinche_driver")->selectRow(array("where"=>"driverid=".$driverid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_driver/show.html");
		}
		public function onAdd(){
			$driverid=get_post("driverid","i");
			if($driverid){
				$data=M("mod_pinche_driver")->selectRow(array("where"=>"driverid=".$driverid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_driver/add.html");
		}
		
		public function onSave(){
			$driverid=get_post("driverid","i");
			$data=M("mod_pinche_driver")->postData();
			if($driverid){
				M("mod_pinche_driver")->update($data,"driverid='$driverid'");
			}else{
				M("mod_pinche_driver")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$driverid=get_post('driverid',"i");
			$status=get_post("status","i");
			M("mod_pinche_driver")->update(array("status"=>$status),"driverid=$driverid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$driverid=get_post('driverid',"i");
			M("mod_pinche_driver")->update(array("status"=>11),"driverid=$driverid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>