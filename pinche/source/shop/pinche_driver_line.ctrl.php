<?php
class pinche_driver_lineControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="driverid=".DRIVERID." AND status in(0,1,2)";
		$url="/moduleshop.php?m=pinche_driver_line";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_pinche_driver_line")->select($option,$rscount);
		 
		if($data){
			foreach($data as $v){
				$lids[]=$v["lineid"];
			}
			$lines=MM("pinche","pinche_line")->getListByIds($lids);
			foreach($data as $k=>$v){
				$p=$lines[$v["lineid"]];
				$p["id"]=$v["id"];
				$p["status"]=$v["status"];
				$data[$k]=$p;
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
		$this->smarty->display("pinche_driver_line/index.html");
	}
	
	public function onSave(){
		$lineid=get("lineid","i");
		$line=M("mod_pinche_line")->selectRow("lineid=".$lineid." AND status=1 ");
		if(empty($line)){
			$this->goAll("路线出错",1);
		}
		$row=M("mod_pinche_driver_line")->selectRow(" driverid=".DRIVERID." AND lineid=".$lineid." AND status in(0,1,2) ");
		if($row){
			$this->goAll("已经添加过了",1);
		}
		M("mod_pinche_driver_line")->insert(array(
			"lineid"=>$lineid,
			"driverid"=>DRIVERID,
			"status"=>0
		));
		$this->goAll("添加成功");
	}
	
	public function onSearch(){
		$keyword=get("keyword","h");
		if(empty($keyword)){
			$this->goAll("请输入内容",1);
		}
		$list=M("mod_pinche_line")->select(array(
			"where"=>" title like '".$keyword."%' AND status=1 ",
			"limit"=>100
		));
		$this->goAll("success",0,array(
			"list"=>$list
		));
	}
	
	public function onDelete(){
		$id=get("id","i");
		$row=M("mod_pinche_driver_line")->selectRow("id=".$id);
		if($row["driverid"]!=DRIVERID){
			$this->goAll("暂无权限",1);
		}
		M("mod_pinche_driver_line")->delete("id=".$id);
		$this->goAll("删除成功");
	}
	
	public function onStatus(){
		$id=get("id","i");
		$row=M("mod_pinche_driver_line")->selectRow("id=".$id);
		if($row["driverid"]!=DRIVERID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]==1){
			$status=2;
		}else{
			$status=1;
		}
		 
		M("mod_pinche_driver_line")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("修改成功");
	}
	
}