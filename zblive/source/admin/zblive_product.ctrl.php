<?php
class zblive_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$config=M("mod_zblive_config")->selectRow();
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$list=M("mod_zblive_product")->select(array(
			"where"=>"room_id=".$room_id." AND status in(0,1,2)  ",
			"order"=>" orderindex ASC"
		));
		$tbs=array(
			"b2c_product",
			"b2b_product",
			"flk_product",
			"wmo2o_product",
			"freeshop_product",
		);
		if(!in_array($zblive["tablename"],$tbs)){
			$this->goAll("暂时不支持产品管理",1);
		}
		if($list){
			foreach($list as $k=>$v){
				$pids[]=$v["productid"];
				
			}
			$arr=explode("_",$zblive["tablename"]);
			
			$tb=str_replace("mod_","",$zblive["tablename"]);
			
			$pros=MM($arr[0],$tb)->getListByIds($pids);
			foreach($list as $k=>$v){
				$p=$pros[$v["productid"]];
				$v["title"]=$p["title"];
				$v["imgurl"]=$p["imgurl"];
				$v["price"]=$p["price"];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"zblive"=>$zblive,
			"list"=>$list,
			"ws_host"=>$config["wshost"],
			"ws_uid"=>base64_encode($_SERVER['HTTP_HOST'].OC_SSID),
			"ws_gid"=>$_SERVER["HTTP_HOST"]."_zblive".$zblive["id"],
		));
		$this->smarty->display("zblive_product/index.html");
	}
	
	public function onSave(){
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$productid=get("productid","i");
		$row=M("mod_zblive_product")->selectRow("room_id=".$room_id." AND status in(0,1,2)  AND productid=".$productid);
		if($row){
			$this->goAll("已经加入了",1);
		}
		M("mod_zblive_product")->insert(array(
			"productid"=>$productid,
			"tablename"=>$zblive["tablename"],
			"room_id"=>$room_id,
			"status"=>1
		));
		$this->updateProids($room_id);
		$this->goAll("加入成功");
	}
	
	public function onChange(){
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$id=get("id","i");
		$orderindex=get("orderindex","i"); 
		M("mod_zblive_product")->update(array(
			"orderindex"=>$orderindex,
			"status"=>1
		),"id=".$id);
		$this->updateProids($room_id);
		$this->goAll("修改成功");
	}
	public function onDelete(){
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$id=get("id","i");
		 
		M("mod_zblive_product")->update(array(
			 
			"status"=>11
		),"id=".$id);
		$this->updateProids($room_id);
		$this->goAll("删除成功");
	}
	
	public function onUp(){
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$id=get("id","i");
		 
		M("mod_zblive_product")->update(array(
			 
			"status"=>1
		),"id=".$id);
		$this->updateProids($room_id);
		$this->goAll("上架成功");
	}
	
	public function onDown(){
		$room_id=get("room_id","i");
		$zblive=M("mod_zblive")->selectRow("id=".$room_id);
		$id=get("id","i");
		 
		M("mod_zblive_product")->update(array(
			 
			"status"=>2
		),"id=".$id);
		$this->updateProids($room_id);
		$this->goAll("下架成功");
	}
	
	public function updateProids($room_id){
		$ids=M("mod_zblive_product")->selectCols(array(
			"where"=>" room_id=".$room_id." AND status=1 ",
			"fields"=>"productid"
		));
		if(empty($ids)){
			$proids="";
		}else{
			$proids=implode(",",$ids);
		}
		M("mod_zblive")->update(array(
			"proids"=>$proids
		),"id=".$room_id);
	}
}

?>