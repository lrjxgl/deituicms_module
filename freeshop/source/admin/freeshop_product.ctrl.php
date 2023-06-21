<?php
class freeshop_productControl extends skymvc{
	
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
		$list=MM("freeshop","freeshop_product")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"type"=>$type
		));
		$this->smarty->display("freeshop_product/index.html");
	}
	 
	public function onAdd(){
		 
		$userid=M("login")->userid;
		 
		$timeList=$this->timeList();
		$this->smarty->assign(array(
			 
			"timeList"=>$timeList
		));
		$this->smarty->display("freeshop_product/add.html");
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
		 
		$config=M("mod_freeshop_config")->selectRow("1");
		$data=M("mod_freeshop_product")->postData();
		$data["content"]=$content=post("content","h");
		
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
		$data["lat"]=$shop["lat"];
		$data["lng"]=$shop["lng"];
		$data["status"]=1;
		 
		$data["createtime"]=date("Y-m-d H:i:s");
		$timeList=$this->timeList();
		$data["etime"]=time()+$timeList[$data["freetime"]]["num"]*60;
		$data["ontime"]=time()+1800;
		
		/*扣除费用*/
		MM("freeshop","freeshop_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$config["post_money"],
			"content"=>"发布产品扣除".$config["post_money"]."元"
		));
		$productid=M("mod_freeshop_product")->insert($data);
		 
		//推送到订阅
		
		$us=M("mod_freeshop_follow")->selectCols(array(
			"fields"=>"userid",
			"where"=>"shopid=".$shopid,
			"limit"=>100000000
		));
	
		if(!$us) $us=array();
		 
		foreach($us as $uid){
			M("mod_freeshop_feeds")->insert(array(
				"userid"=>$uid,
				"productid"=>$productid,
				"shopid"=>$shopid,
				"dateline"=>time(),
			));
		}
		
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		 
		$id=get("productid","i");
		$row=M("mod_freeshop_product")->selectRow("productid=".$id);
		 
		M("mod_freeshop_product")->update(array("status"=>11),"productid=".$id);
		 
		//删除所有关注的
		M("mod_freeshop_feeds")->delete("productid=".$id);
		 
		$this->goAll("删除成功");
	}
	
	public function onRecommend(){
		$productid=get_post('productid',"i");
		$row=M("mod_freeshop_product")->selectRow("productid=".$productid);
		$status=1;
		if($row["isrecommend"]==1){
			$status=0;
		}
		M("mod_freeshop_product")->update(array(
			"isrecommend"=>$status
		),"productid=".$productid);
		$this->goAll("success",0,$status);
	}
	
}