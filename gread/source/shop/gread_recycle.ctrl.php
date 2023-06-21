<?php
class gread_recycleControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="shopid=".SHOPID;
		$type=get("type","h");
		switch($type){
			case "new":
				$where.=" AND status=0 ";
				break;
			case "confirm":
				$where.=" AND status=1 ";
				break;
			case "send":
				$where.=" AND status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;
			case "cancel":
				$where.=" AND status=4 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
				break;
		}
		$url="/moduleshop.php?m=gread_recycle&type=".$type;
		$limit=12;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_gread_recycle")->select($option,$rscount);
		$statusList=array(
			0=>"未接单",
			1=>"处理中",
			3=>"已完成",
			4=>"已取消"
		);
		if($data){
			foreach($data as $k=>$v){
				$v["status_name"]=$statusList[$v["status"]];
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
		 
		$this->smarty->display("gread_recycle/index.html");
	}
	
	public function onAccept(){
		$id=get("id","i");
		$row=M("mod_gread_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=0){
			$this->goAll("已处理",1);
		}
		M("mod_gread_recycle")->update(array(
			"status"=>1
		),"id=".$id);
		$this->goAll("接单成功");
		
	}
	
	public function onFinish(){
		$id=get("id","i");
		$row=M("mod_gread_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_gread_recycle")->update(array(
			"status"=>3
		),"id=".$id);
		$this->goAll("回收完成");
	}
	
	public function onCancel(){
		$id=get("id","i");
		$row=M("mod_gread_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_gread_recycle")->update(array(
			"status"=>4
		),"id=".$id);
		$this->goAll("回收取消");
	}
	
}
?>