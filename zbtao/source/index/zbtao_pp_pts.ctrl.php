<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_pp_ptsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zbtao_pp_pts&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ptid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zbtao_pp_pts")->select($option,$rscount);
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
			$this->smarty->display("zbtao_pp_pts/index.html");
		}
		
		public function onMy(){
			 
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$where=" ppid=".$pp["ppid"]." AND status in(0,1,2) ";
			$url="/moduleadmin.php?m=zbtao_pp_pts&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ptid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zbtao_pp_pts")->select($option,$rscount);
			$ptcomList=MM("zbtao","zbtao_pp_pts")->ptcomList();
			if(!empty($data)){
				foreach($data as $k=>$v){
					$v["ptcom_name"]=$ptcomList[$v["ptcom"]];
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
					"ptcomList"=>$ptcomList
				)
			);
			$this->smarty->display("zbtao_pp_pts/my.html");
		}
		
		public function onAdd(){
			$ptid=get_post("ptid","i");
			if($ptid){
				$data=M("mod_zbtao_pp_pts")->selectRow(array("where"=>"ptid=".$ptid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_pp_pts/add.html");
		}
		
		public function onSave(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			
			$ptid=get_post("ptid","i");
			$data=M("mod_zbtao_pp_pts")->postData();
			if($ptid){
				$row=M("mod_zbtao_pp_pts")->selectRow("ptid=".$ptid);
				if($row["ppid"]!=$pp["ppid"]){
					$this->goAll("暂无权限",1);
				}
				M("mod_zbtao_pp_pts")->update($data,"ptid='$ptid'");
			}else{
				$data["ppid"]=$pp["ppid"];
				M("mod_zbtao_pp_pts")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$ptid=get_post('ptid',"i");
			$status=get_post("status","i");
			$row=M("mod_zbtao_pp_pts")->selectRow("ptid=".$ptid);
			if($row["ppid"]!=$pp["ppid"]){
				$this->goAll("暂无权限",1);
			}
			M("mod_zbtao_pp_pts")->update(array("status"=>$status),"ptid=$ptid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$pp=MM("zbtao","zbtao_pp")->getLogin();
			$ptid=get_post('ptid',"i");
			$row=M("mod_zbtao_pp_pts")->selectRow("ptid=".$ptid);
			if($row["ppid"]!=$pp["ppid"]){
				$this->goAll("暂无权限",1);
			}
			M("mod_zbtao_pp_pts")->update(array("status"=>11),"ptid=$ptid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>