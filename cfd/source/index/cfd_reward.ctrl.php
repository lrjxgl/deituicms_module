<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cfd_rewardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			
			
			$cfdid=get_post('cfdid','i');
			$where=" status<3 AND cfdid=".$cfdid;
			$cfd=M("mod_cfd")->selectRow("cfdid=".$cfdid);
			$url="/module.php?m=cfd_reward&cfdid=".$cfdid;
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd_reward")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
					"cfd"=>$cfd
				)
			);
			$this->smarty->display("cfd_reward/index.html");
		}
		public function onSave(){
			$userid=M("login")->userid;
			$id=get_post("id","i");

			$data=M("mod_cfd_reward")->postData();
			$data['typeid']=isset($data['typeid'])?1:0;
			$data["status"]=1;
			
			if($id){
				$row=M("mod_cfd_reward")->selectRow("id=".$id);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_cfd_reward")->update($data,"id=".$id);
			}else{
				$data["userid"]=$userid;
				$data["dateline"]=time();
				M("mod_cfd_reward")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_cfd_reward")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_cfd_reward")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goall("保存成功",0,$status);
		} 
		
		public function onDelete(){
			$id=get_post('id',"i");
			$userid=M("login")->userid;
			$row=M("mod_cfd_reward")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_cfd_reward")->update(array(
				"status"=>11
			),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>