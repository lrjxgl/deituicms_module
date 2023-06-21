<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_pp_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "pass":
						$where=" status=1 ";
					break;
				case "forbid":
						$where=" status=2 ";
					break;
				default:
						$where=" status=0 ";
					break;
			}
			 
			$url="/moduleadmin.php?m=zbtao_pp_apply&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ppid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_pp_apply")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_pp_apply/index.html");
		}
		
		public function onStatus(){
			$ppid=get_post('ppid',"i");
			$status=get_post("status","i");
			M("mod_zbtao_pp_apply")->update(array("status"=>$status),"ppid=$ppid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$ppid=get_post('ppid',"i");
			M("mod_zbtao_pp_apply")->update(array("status"=>11),"ppid=$ppid");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$ppid=get_post('ppid',"i");
			$row=M("mod_zbtao_pp_apply")->selectRow("ppid=".$ppid);
			if(empty($row) || $row["status"]!=0){
				$this->goAll("已经处理过了",1);
			}
			M("mod_zbtao_pp_apply")->begin();
			M("mod_zbtao_pp_apply")->update(array(
				"status"=>1
			),"ppid=".$ppid);
			M("mod_zbtao_pp")->insert(array(
				"nickname"=>$row["nickname"],
				"imgurl"=>$row["imgurl"],
				"userid"=>$row["userid"],
				"gender"=>$row["gender"],
				"description"=>$row["description"],
				"createtime"=>date("Y-m-d H:i:s0")
			));
			M("mod_zbtao_pp_apply")->commit();
			$this->goAll("处理完成");
		}
		
		public function onForbid(){
			$ppid=get_post('ppid',"i");
			$row=M("mod_zbtao_pp_apply")->selectRow("ppid=".$ppid);
			if(empty($row) || $row["status"]!=0){
				$this->goAll("已经处理过了",1);
			}
			M("mod_zbtao_pp_apply")->update(array(
				"status"=>2
			),"ppid=".$ppid);
			$this->goAll("处理完成");
		}
		
	}

?>