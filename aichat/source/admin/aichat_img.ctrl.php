<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_imgControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "new":
					$where="   status=0 ";
					$type_name="待审核";
					break;
				case "forbid":
					$where="   status=2 ";
					$type_name="已禁止";
					break;
				case "pass":
					$where="  status=1 ";
					$type_name="已通过";
					break;
				default:
					$where=" status in(0,1,2)";
					$type_name="全部";
					break;
			}
			$where.=" AND create_status=1 ";
			$url="/moduleadmin.php?m=aichat_img&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_img")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as $k=>$v){
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
					"type"=>$type,
					"type_name"=>$type_name
				)
			);
			$this->smarty->display("aichat_img/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_aichat_img")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_img/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_aichat_img")->postData();
			if($id){
				M("mod_aichat_img")->update($data,"id=".$id);
			}else{
				M("mod_aichat_img")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_aichat_img")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_img")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_aichat_img")->selectRow("id=".$id);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_img")->update(array("isrecommend"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onHot(){
			$id=get_post('id',"i");
			$row=M("mod_aichat_img")->selectRow("id=".$id);
			if($row["ishot"]==1){
				$status=0;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_img")->update(array("ishot"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_aichat_img")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>