<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_liveControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "online":
					$where=" status=1 ";
					break;
				case "offline":
					$where=" status=2 ";
					break;
				case "all":
					$where=" status in(0,1,2)";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			$url="/moduleadmin.php?m=zbtao_live&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" liveid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live/index.html");
		}
		
		public function onAdd(){
			$liveid=get_post("liveid","i");
			if($liveid){
				$data=M("mod_zbtao_live")->selectRow(array("where"=>"liveid=".$liveid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_live/add.html");
		}
		
		public function onSave(){
			$liveid=get_post("liveid","i");
			$data=M("mod_zbtao_live")->postData();
			if($liveid){
				M("mod_zbtao_live")->update($data,"liveid='$liveid'");
			}else{
				M("mod_zbtao_live")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$liveid=get_post('liveid',"i");
			$status=get_post("status","i");
			$row=M("mod_zbtao_live")->selectRow("liveid=".$liveid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_zbtao_live")->update(array("status"=>$status),"liveid=$liveid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$liveid=get_post('liveid',"i");
			 
			$row=M("mod_zbtao_live")->selectRow("liveid=".$liveid);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_zbtao_live")->update(array("isrecommend"=>$status),"liveid=$liveid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$liveid=get_post('liveid',"i");
			M("mod_zbtao_live")->update(array("status"=>11),"liveid=$liveid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>