<?php
class car_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	 
	
	public function onDefault(){
		 
		$start=get("per_page","i");
		$limit=24;
		
		$type=get("type","h");
		switch($type){
			case "online":
				$where=" status=1 ";
				break;
			case "offline":
				$where=" status=2 ";
				break;
			case "del":
				$where=" status=11 ";
				break;
			case "recommend":
				$where=" isrecommend=1 AND status=1 ";
				break;
			case "new":
				$where=" sitecheck=0 ";
				break;
			default:
				$where=" status in(0,1,2) ";
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>" productid DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("car","car_product")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$groupList=M("mod_car_group")->select(array(
			"where"=>" status=1 "
		));
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"type"=>$type,
			"groupList"=>$groupList
		));
		$this->smarty->display("car_product/index.html");
	}
	 
	public function onAdd(){
		 
		 
		$timeList=$this->timeList();
		$config=M("mod_car_config")->selectRow("1");
		$catList=MM("car","car_product")->catList();
		$brandList=MM("car","car_brand")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$id=get_post("productid","i");
		$imgsdata=array();
		if($id){
			$data=M("mod_car_product")->selectRow("productid=".$id);
			 
			$data["true_mp4url"]=images_site($data["mp4url"]);
			
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
		
		 
		
		$this->smarty->assign(array(
			"data"=>$data,
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList,
			"brandList"=>$brandList,
			"imgsdata"=>$imgsdata
		));
		$this->smarty->display("car_product/add.html");
	}
	public function timeList(){
		return array(
			1=>["num"=>30,"title"=>"30分钟"],
			2=>["num"=>60,"title"=>"60分钟"],
			3=>["num"=>120,"title"=>"2小时"],
			4=>["num"=>180,"title"=>"3小时"],
			5=>["num"=>720,"title"=>"12小时"],
		);
	}
	
	public function onSave(){
		
		$config=M("mod_car_config")->selectRow("1");
		$data=M("mod_car_product")->postData();
		$data["content"]=$content=post("content","x");
		if(empty($data["content"])){
			$this->goAll("请输入产品内容",1);
		}
		if(empty($data["title"])){
			$this->goAll("请输入主题",1);
		}
		if(empty($data["pai_date"])){
			$this->goAll("请设置上牌时间",1);
		}
		$imgsdata=post("imgsdata","h");
		if($imgsdata){
			$ims=explode(",",$imgsdata);
			foreach($ims as $im){
				if($im!="undefined" && $im!=""){
					$imgs[]=$im;
				}
			}
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
				$data["imgsdata"]=implode(",",$imgs);
			}	
		}
	 
		$data["status"]=1;
		
		 
		if($data["money"]==0){
			$this->goAll("请设置价格",1);
		}
		$productid=post("productid","i");
		M("mod_car_product")->update($data,"productid=".$productid);
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		 
		$id=get("productid","i");
		$row=M("mod_car_product")->selectRow("productid=".$id);
		 
		M("mod_car_product")->update(array("status"=>11),"productid=".$id);
		 
		//删除所有关注的
		M("mod_car_feeds")->delete("productid=".$id);
		 
		$this->goAll("删除成功");
	}
	
	public function onRecommend(){
		$productid=get_post('productid',"i");
		$row=M("mod_car_product")->selectRow("productid=".$productid);
		$status=1;
		if($row["isrecommend"]==1){
			$status=0;
		}
		M("mod_car_product")->update(array(
			"isrecommend"=>$status
		),"productid=".$productid);
		$this->goAll("success",0,$status);
	}
	
	public function onGroup(){
		$ids=post('ids','i');
		$gid=post("gid","i");
		if(!$gid){
			$this->goAll("请选择归类",1);
		}
		if(empty($ids)){
			$this->goAll("请选择产品",1);
		}
		$hasids=M("mod_car_group_product")->selectCols(array(
			"where"=>" gid=".$gid." AND productid in("._implode($ids).") ",
			"fields"=>"productid"
		));
		$newids=$ids;
		if($hasids){
			$newids=array_diff($ids,$hasids);
		}
		if(!empty($newids)){
			foreach($newids as $productid){
				M("mod_car_group_product")->insert(array(
					"gid"=>$gid,
					"productid"=>$productid,
					"orderindex"=>11
				));
			}
		}
		$this->goAll("success");
	}
	
	public function onPass(){
		$productid=get("productid","i");
		M("mod_car_product")->update(array(
			"sitecheck"=>1
		),"productid=".$productid);
		$this->goAll("success");
	}
	public function onForbid(){
		$productid=get("productid","i");
		M("mod_car_product")->update(array(
			"sitecheck"=>2,
			"status"=>4
		),"productid=".$productid);
		$this->goAll("success");
	}
	
}