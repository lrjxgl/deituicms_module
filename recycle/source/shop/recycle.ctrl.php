<?php
class recycleControl extends skymvc{
	
	 
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
		$url="/moduleshop.php?m=recycle&type=".$type;
		$limit=12;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("recycle","recycle")->select($option,$rscount);
		$statusList=MM("recycle","recycle")->statusList();
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
		 
		$this->smarty->display("recycle/index.html");
	}
	
	public function onShow(){
		$id=get("id","i");
		$row=MM("recycle","recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		$statusList=MM("recycle","recycle")->statusList();
		$row["status_name"]=$statusList[$row["status"]];
		$logList=M("mod_recycle_log")->select(array(
			"where"=>"recycleid=".$id,
			"order"=>"id ASC"
		));
		$raty=[];
		if($row["israty"]){
			$raty=M("mod_recycle_raty")->selectRow("recycleid=".$id);
		}
		$this->smarty->goAssign(array(
			"data"=>$row,
			"logList"=>$logList,
			"raty"=>$raty
		));
		$this->smarty->display("recycle/show.html");
	}
	
	public function onAccept(){
		$id=get("id","i");
		$row=M("mod_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=0){
			$this->goAll("已处理",1);
		}
		M("mod_recycle")->update(array(
			"status"=>1
		),"id=".$id);
		
		M("mod_recycle_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"recycleid"=>$id,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"回收订单[".$id."]确认接单"
		));
		$this->goAll("接单成功");
		
	}
	
	public function onSend(){
		$id=get("id","i");
		$row=M("mod_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=1){
			$this->goAll("处理失败",1);
		}
		M("mod_recycle")->update(array(
			"status"=>2
		),"id=".$id);
		M("mod_recycle_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"recycleid"=>$id,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"回收订单[".$id."]取货中"
		));
		$this->goAll("取货中");
		
	}
	
	public function onFinish(){
		$id=get("id","i");
		$row=M("mod_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_recycle")->update(array(
			"status"=>3
		),"id=".$id);
		M("mod_recycle_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"recycleid"=>$id,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"回收订单[".$id."]处理完成"
		));
		$this->goAll("回收完成");
	}
	
	public function onCancel(){
		$id=get("id","i");
		$row=M("mod_recycle")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]>=3){
			$this->goAll("已处理",1);
		}
		M("mod_recycle")->update(array(
			"status"=>4
		),"id=".$id);
		M("mod_recycle_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>SHOPID,
			"recycleid"=>$id,
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>"回收订单[".$id."]商家取消了"
		));
		$this->goAll("回收取消");
	}
	
}
?>