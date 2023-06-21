<?php
class gread_zbookControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$type=get("type","h");
		switch($type){
			case "new":
				$where="   status=0 ";
				break;
			case "confirm":
				$where="   status=1 ";
				break;
			case "send":
				$where="   status=2 ";
				break;
			case "finish":
				$where="   status=3 ";
				break;
			case "cancel":
				$where="   status=4 ";
				break;
			default:
				$where="   status in(0,1,2,3,4) ";
				break;
		}
		$url="/moduleshop.php?m=gread_zbook&type=".$type;
		$limit=12;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_gread_zbook")->select($option,$rscount);
		$statusList=array(
			0=>"未接单",
			1=>"处理中",
			3=>"已完成",
			4=>"已取消"
		);
		if($data){
			foreach($data as $k=>$v){
				$v["status_name"]=$statusList[$v["status"]];
				$imgs=[];
				if(!empty($v["imgsdata"])){
					$imgs=explode(",",$v["imgsdata"]);
					foreach($imgs as $ik=>$im){
						$im=images_site($im);
						$imgs[$ik]=$im;
					}
					 
				}
				$v["imgList"]=$imgs;
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
				"type"=>$type
			)
		);
		 
		$this->smarty->display("gread_zbook/index.html");
	}
	
	 
	
	public function onCancel(){
		$id=get("id","i");
		$row=M("mod_gread_zbook")->selectRow("id=".$id);
		 
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_gread_zbook")->update(array(
			"status"=>4
		),"id=".$id);
		$this->goAll("回收取消");
	}
	
}
?>