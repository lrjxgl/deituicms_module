<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cy2c_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=cy2c_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cy2c_place")->select($option,$rscount);
			if($data){
				require_once "extends/hashids/Hashids.php";
				$config=MM("cy2c","cy2c_config")->get();
				$hashids = new Hashids\Hashids($config["md5code"], 6);
				$icode=$hashids->encode($placeid);
				foreach($data as $k=>$v){
					$placecode=$hashids->encode($v["placeid"]);
					$v["url"]=HTTP_HOST."/module.php?m=cy2c_place&a=set&placecode=".$placecode;
					$v["ewm"]="/index.php?m=qrcode&content=".urlencode($v["url"]);
					
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
			$this->smarty->display("cy2c_place/index.html");
		}
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_cy2c_place")->selectRow(array("where"=>"placeid=".$placeid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cy2c_place/add.html");
		}
		
		public function onSave(){
			$placeid=get_post("placeid","i");
			$data=M("mod_cy2c_place")->postData();
			if($placeid){
				M("mod_cy2c_place")->update($data,"placeid='$placeid'");
			}else{
				M("mod_cy2c_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_cy2c_place")->selectRow("placeid=".$placeid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_cy2c_place")->update(array(
				"status"=>$status
			),"placeid=".$placeid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			M("mod_cy2c_place")->update(array("status"=>11),"placeid=$placeid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>