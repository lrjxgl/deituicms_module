<?php
class flk_oneControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$url="/moduleadmin.php?m=flk_one";
		$limit=20;
		$start=get("per_page","i");
		$sarr=array("id");
		$day=date("Y-m-d H:i:s");
		switch(get("type")){
			case "all":
				$where=" status=1 AND one_on=1";
				break;
			case "forbid":
				$where="  one_on=2 ";
				break;
			case "will":
				$where=" one_status=1 AND one_on=1 AND one_stime>'".$day."' " ;
				break;
			case "doing":
				$where=" one_status=1 AND one_on=1 AND one_stime<='".$day."' AND one_etime>='".$day."' " ;
				break;
			case "finish":
				$where=" one_status=1 AND one_on=1 AND one_etime<'".$day."' " ;
				break;
			default:
				$where=" status=1 AND one_on=1 AND one_status=0 ";
				break;
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_flk_product")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$cids[]=$v["catid"];
			}
			$cats=MM("flk","flk_category")->getListByIds($cids);
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v["imgurl"]);
				$v["catid_name"]=$cats[$v["catid"]]["title"];
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$groupList=M("mod_flk_group")->select(array(
			"where"=>" status=1"
		));	
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"groupList"=>$groupList,
			)
		);
		$this->smarty->display("flk_one/index.html");
	}
	
	public function onAdd(){
		$id=get_post("id","i");
		 
		$data=M("mod_flk_product")->selectRow(array("where"=>"id=".$id));
		
		$this->smarty->goassign(array(
			"data"=>$data
			
		));
		$this->smarty->display("flk_one/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$product=M("mod_flk_product")->selectRow(array("where"=>"id=".$id." AND shopid=".SHOPID));
		if(empty($product)){
			$this->goAll("产品不存在",1);
		}
		 
		
		$data=M("mod_flk_product")->postData();
		if($data["one_price"]==0 || $data["one_price"]>$product["price"]){
			$this->goAll("秒杀价应该便宜点",1);
		}
		$data["one_on"]=1;
	 
		if($data["one_flk_discount"]>$data["one_discount"]){
			$this->goAll("折扣不对",1);
		} 
		M("mod_flk_product")->update($data,"id=".$id);
		$this->goall("保存成功");
	}
	
	public function onPass(){
		$id=get_post("id","i"); 
		$data=M("mod_flk_product")->selectRow("id=".$id);
		if($data["status"]!=1){
			$this->goAll("产品未上架",1);
		}
		M("mod_flk_product")->update(array(
			"one_status"=>1
		),"id=".$id);
		$this->goAll("审核成功");
	}
	
	public function onForbid(){
		$id=get_post("id","i"); 
		$data=M("mod_flk_product")->selectRow("id=".$id);
		if($data["status"]!=1){
			$this->goAll("产品未上架",1);
		}
		M("mod_flk_product")->update(array(
			"one_status"=>2
		),"id=".$id);
		$this->goAll("审核成功");
	}
}
?>