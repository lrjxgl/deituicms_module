<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_huxingControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$lpid=get("lpid","i");
			$loupan=M("mod_house_loupan")->selectRow("id=".$lpid);
			$where=" lpid=".$lpid." AND status in(0,1,2)";
			$url="/moduleadmin.php?m=house_huxing&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_huxing")->select($option,$rscount);
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
					"loupan"=>$loupan
				)
			);
			$this->smarty->display("house_huxing/index.html");
		}
		
		public function onAdd(){
			$lpid=get("lpid","i");
			$loupan=M("mod_house_loupan")->selectRow("id=".$lpid);
			$id=get_post("id","i");
			if($id){
				$data=M("mod_house_huxing")->selectRow(array("where"=>"id=".$id));
				if($data["imgsdata"]){
						$imgs=explode(",",$data["imgsdata"]);
						foreach($imgs as $v){
							$imgsdata[]=array(
								"imgurl"=>$v,
								"trueimgurl"=>images_site($v)
							);
						}
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"loupan"=>$loupan
			));
			$this->smarty->display("house_huxing/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_house_huxing")->postData();
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
					$data["imgurl"]=$imgsdata[0];
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			}
			$data["status"]=1;
			if($id){
				M("mod_house_huxing")->update($data,"id='$id'");
			}else{
				M("mod_house_huxing")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_house_huxing")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_huxing")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>