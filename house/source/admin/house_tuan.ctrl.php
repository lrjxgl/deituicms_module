<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_tuanControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$type_name="全部看房团";
			switch($type){
				case "new":
					$where=" status=0 ";
					$type_name="待审看房团";
					break;
				case "pass":
					$where=" status=1 ";
					$type_name="上架看房团";
					break;
				case "forbid":
					$where=" status=2 ";
					$type_name="下架看房团";
					break;
				default:
					break;
				
			}
			$url="/moduleadmin.php?m=house_tuan&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_tuan")->select($option,$rscount);
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
			$this->smarty->display("house_tuan/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_house_tuan")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("house_tuan/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_house_tuan")->postData();
			if($id){
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_house_tuan")->update($data,"id='$id'");
			}else{
				M("mod_house_tuan")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_house_tuan")->selectRow(array("where"=>"id=".$id));
			$status=1;
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_house_tuan")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_tuan")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>