<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$url="/moduleadmin.php?m=fishing_place&type=".$type;
			if($type=="check"){
				$where="status=0 ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_place")->Dselect($option,$rscount);
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
					"url"=>$url
				)
			);
			if($type=="check"){
				$this->smarty->display("fishing_place/check.html");
			}else{
				$this->smarty->display("fishing_place/index.html");
			}
			
		}
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			$imgsdata=[];
			if($placeid){
				$data=M("mod_fishing_place")->selectRow(array("where"=>"placeid=".$placeid));
				$imgsdata=parseImgsData($data["imgsdata"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("fishing_place/add.html");
		}
		
		public function onSave(){
			$placeid=get_post("placeid","i");
			$data=M("mod_fishing_place")->postData();
			$imgsdata=post("imgsdata","h");
			if(!empty($imgsdata)){
				$imgsdata=safeImgsData($imgsdata);
				$data["imgsdata"]=$imgsdata;
				$ex=explode(",",$imgsdata);
				$data["imgurl"]=$ex[0];
			}
			if($placeid){
				M("mod_fishing_place")->update($data,"placeid='$placeid'");
			}else{
				M("mod_fishing_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_fishing_place")->where("placeid=".$placeid)->row();
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_fishing_place")->update(array("status"=>$status),"placeid=$placeid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			M("mod_fishing_place")->update(array("status"=>11),"placeid=$placeid");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$placeid=get_post('placeid',"i");
			M("mod_fishing_place")->update(array("status"=>1),"placeid=".$placeid);
			$this->goAll("审核通过");
		}
		
		public function onForbid(){
			$placeid=get_post('placeid',"i");
			M("mod_fishing_place")->update(array("status"=>2),"placeid=".$placeid);
			$this->goAll("审核不通过");
		}
		
		
	}

?>