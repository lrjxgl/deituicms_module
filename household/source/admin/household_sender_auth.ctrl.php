<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_sender_authControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
		
			$url="/moduleadmin.php?m=household_sender_auth&a=default";
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
					case "new":
						$where=" status=0 ";
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
			$data=M("mod_household_sender_auth")->select($option,$rscount);
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
			$this->smarty->display("household_sender_auth/index.html");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$row=M("mod_household_sender_auth")->selectRow("id=".$id);
			M("mod_household_sender_auth")->update(array(
				"status"=>1
			),"id=".$id);
			M("mod_household_sender")->update(array(
				"isauth"=>1
			),"senderid=".$row["senderid"]); 
			$this->goall("审核通过");
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			$row=M("mod_household_sender_auth")->selectRow("id=".$id);
			M("mod_household_sender_auth")->update(array(
				"status"=>2
			),"id=".$id);
			 
			$this->goall("操作成功");
		}
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_household_sender_auth")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>