<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_sender_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
		
			$url="/moduleadmin.php?m=household_sender_apply&a=default";
			$type=get("type","h");
			if($type){
				$url.="&type=".$type;
				switch($type){
					case "pass":
						$where="  status=1 ";
						break;
					case "forbid":
						$where="  status=2 ";
						break;
					default:
						$where=" status in(0,1,2)";
						break;
				}	
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
			$data=M("mod_household_sender_apply")->select($option,$rscount);
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
			$this->smarty->display("household_sender_apply/index.html");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$row=M("mod_household_sender_apply")->selectRow("id=".$id);
			$sender=M("mod_household_sender")->selectRow("telephone='".$row["telephone"]."' ");
			if($sender){
				$this->goAll("当前手机号已经被占用了",1);
			}
			$sender=M("mod_household_sender")->selectRow("userno='".$row["userno"]."' ");
			if($sender){
				$this->goAll("当前身份证已经被占用了",1);
			}
			M("mod_household_sender_apply")->update(array("status"=>1),"id=$id");
			M("mod_household_sender")->insert(array(
				"telephone"=>$row["telephone"],
				"nickname"=>$row["truename"],
				"truename"=>$row["truename"],
				"userno"=>$row["userno"]
			));
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_household_sender_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>