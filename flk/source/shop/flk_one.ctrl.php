<?php 
class flk_oneControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		 
	}
	public function onDefault(){
		$where=" status in(0,1,2) AND shopid=".SHOPID;
		$url="/moduleshop.php?m=flk_product&a=default";
		$limit=20;
		$start=get("per_page","i");
		$sarr=array("id","isrecommend","ishot");
		foreach($_GET as $k=>$v){
			if($_GET[$k] && in_array($k,$sarr)){
				$where.=" AND ".$k."='".get($k,'x')."' ";
				$url.="&".$k."=".urlencode(get($k));
			}
		}
		$bstatus=get("bstatus","i");
		switch($bstatus){
			case 1:
				$where.=" AND status=1 ";
				break;
			case 2:
				$where.=" AND status in(0,2) ";
				break;
		} 
		$stime=get('stime','h');
		if($stime){
			$where.=" AND createtime>='".$stime."' ";
		}
		$etime=get('etime','h');
		if($etime){
			$where.=" AND createtime<='".$etime."'";
		}
		$catid=get_post('catid','i');
		if($catid){
			$cids=MM("b2b","b2b_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
			$url.="&catid=".$catid;
		}
		$title=get('title','h');
		if($title){
			$where.=" AND title like '%".$title."%'";
			$url.="&title=".urlencode($title);
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
				$cids[]=$v["shop_catid"];
			}
			$cats=MM("flk","flk_shop_product_category")->getListByIds($cids);
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v["imgurl"]);
				$v["catid_name"]=$cats[$v["shop_catid"]]["title"];
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$catList=MM("flk","flk_shop_product_category")->children(SHOPID,0);
	 
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"catList"=>$catList
			)
		);
		$this->smarty->display("flk_one/index.html");
	}
	
	public function onAdd(){
		$id=get_post("id","i");
		$imgsdata=array();
		$data=M("mod_flk_product")->selectRow(array("where"=>"id=".$id." AND shopid=".SHOPID));
		
		if(!$data["one_on"]){
			 
			$data["one_etime"]=date("Y-m-d H:i:s");
			$data["one_stime"]=date("Y-m-d H:i:s");
		}
		
		$this->smarty->goassign(array(
			"data"=>$data
			
		));
		$this->smarty->display("flk_one/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$product=M("mod_flk_product")->selectRow(array("where"=>"id=".$id." AND shopid=".SHOPID));
		if(empty($product)){
			$this->goAll("产不不存在",1);
		}
		if($product["one_status"]!=0){
			$this->goAll("该产品无法参与",1);
		}

		$data=M("mod_flk_product")->postData();
		if($data["one_price"]==0 || $data["one_price"]>$product["price"]){
			$this->goAll("秒杀价应该便宜点",1);
		}
		$data["one_on"]=1;
		 
		$data["one_status"]=0;
		M("mod_flk_product")->update($data,"id=".$id);
		$this->goall("保存成功");
	}
	
	
	
}