<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class recycle_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h"); 
			$url="/moduleadmin.php?m=recycle_shop_apply&type=".$type;
			$limit=20;
			$start=get("per_page","i");
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
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_recycle_shop_apply")->select($option,$rscount);
			 
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
			$this->smarty->display("recycle_shop_apply/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_recycle_shop_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_recycle_shop_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get("id","i");
			$row=M("mod_recycle_shop_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			$_POST=$row;
			$indata=M("mod_recycle_shop")->postData();
			 
			$indata["status"]=1;
			unset($indata["id"]);
			 
			$shopid=M("mod_recycle_shop")->insert($indata);
			 
			M("mod_recycle_shop_apply")->update(array(
				"status"=>1
			),"id=".$id);
			//发送通知
			M("notice")->add(array(
				"content"=>"您在物品回收申请入驻，审核通过了",
				"userid"=>$row["userid"],
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"recycle"
				)
			)); 
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$id=get("id","i");
			$row=M("mod_recycle_shop_apply")->selectRow("id=".$id);
				if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			M("mod_recycle_shop_apply")->update(array(
				"status"=>2
			),"id=".$id);
			//发送通知
			M("notice")->add(array(
				"content"=>"您在物品回收申请入驻，审核失败，请重新提交",
				"userid"=>$row["userid"],
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"recycle",
					"a"=>"default"
					
				)
			)); 
			$this->goAll("禁止成功");
		}
	}

?>