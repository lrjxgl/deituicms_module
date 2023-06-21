<?php
class b2c_flashControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$type=get("type","h");
		$where=" status=1 AND otype='isflash' ";
		$url="/module.php?m=b2c_flash";
		$limit=get("limit","i");
		$limit=$limit==0?20:$limit;
		$start=get("per_page","i");
		$order=" id DESC"; 
		switch($type){
			case "finish":
				$where.=" AND etime<".time();				
				break;
			case "unbegin":
				$where.=" AND stime>".time();
				break;
				
			case "doing":
				$where.=" AND stime<".time()." AND etime>".time();
				$order=" etime ASC";
				break;
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"where"=>$where
		);
		$rscount=true;
		$data=MM("b2c","b2c_product")->Dselect($option,$rscount);
		$time=0;
		if($data){
			 
			$time=$data[0]["etime"]-time();
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
				"url"=>$url,
				"time"=>$time
			)
		);
		$this->smarty->display("b2c_flash/index.html");
	}
	//抢购排队
	public function onOrder(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$productid=get_post("productid","i");
		$ksid=get_post("ksid","i");
		$product=MM("b2c","b2c_product")->selectRow("id=".$productid);
		if(empty($product) || $product["status"]!=1){
			$this->goAll("产品已经下线了",1);
		}
		$queue=M("mod_b2c_flash_queue")->selectRow("userid=".$userid." AND productid=".$productid);
		if($queue){
			if($queue["status"]==0){
				$this->goAll("success");
			}else{
				$this->goAll("你已经抢过了",1);
			}		
		}
		
		if($product["total_num"]<=0){
			$this->goAll("已被抢光了",1,$product);
		}
		
		M("mod_b2c_flash_queue")->insert(array(
			"userid"=>$userid,
			"ksid"=>$ksid,
			"productid"=>$productid,
			"createtime"=>date("Y-m-d H:i:s"),
			"status"=>0
		));
		$this->goAll("success");
		
	}
	
	
}