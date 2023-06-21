<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_shop_agentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="shopid=".SHOPID." AND status in(0,1,2)";
			$url="moduleshop.php?m=csc_shop_agent&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" agentid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_shop_agent")->select($option,$rscount);
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
			$this->smarty->display("csc_shop_agent/index.html");
		}
		
		public function onAdd(){
			$agentid=get_post("agentid","i");
			if($agentid){
				$data=M("mod_csc_shop_agent")->selectRow(array("where"=>"agentid=".$agentid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("csc_shop_agent/add.html");
		}
		
		public function onSave(){
			$agentid=get_post("agentid","i");
			$data=M("mod_csc_shop_agent")->postData();
			$data["shopid"]=SHOPID;
			if($agentid){
				$row=M("mod_csc_shop_agent")->selectRow("agentid=".$agentid);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_csc_shop_agent")->update($data,"agentid='$agentid'");
			}else{
				M("mod_csc_shop_agent")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$agentid=get_post('agentid',"i");
			$status=get_post("status","i");
			$row=M("mod_csc_shop_agent")->selectRow("agentid=".$agentid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_shop_agent")->update(array("status"=>$status),"agentid=$agentid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$agentid=get_post('agentid',"i");
			$row=M("mod_csc_shop_agent")->selectRow("agentid=".$agentid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_shop_agent")->update(array("status"=>11),"agentid=$agentid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>