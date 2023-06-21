<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ershou_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=ershou_product&a=default";
			$type=get("type","h");
			$order=" productid DESC";
			switch($type){
				case "new":
					$order=" updatetime DESC ";
					break;
				case "buy":
					$order=" sold_num DESC ";
					break;
				case "new":
					$order=" createtime DESC ";
					break;
				default:
					 
					break;
					
			}
			$tagname=get('tagname',"h");
			if(!empty($tagname)){
				$where.=" AND description like '%".$tagname."%' ";
			}
			$keyword=get('keyword',"h");
			if(!empty($keyword)){
				$where.=" AND description like '%".$keyword."%' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ershou_product")->select($option,$rscount);
			if(!empty($data)){
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
					"url"=>$url
				)
			);
			$this->smarty->display("ershou_product/index.html");
		}
		
		public function onList(){
			$catid=get("catid","i");
			$where=" status=1 ";
			$url="/module.php?m=ershou_product&a=list";
			if($catid){
				$cids=MM("ershou","ershou_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).")";
			}
			$limit=20;
			$start=get("per_page","i");
			$orderby=get("orderby","h");
			$order=" productid DESC";
			switch($orderby){
				case "priceDesc":
					$order=" price DESC";
					break;
				case "priceAsc":
					$order=" price ASC";
					break;
				case "updatetime":
					$order=" updatetime DESC";
					break;
				case "distance":
					 
					break;
				case "new":
					$order=" productid DESC";
					break;
				case "raty":
					break;
				default:
					$order=" productid DESC";
					break;
			}
			//位置服务
			$cityid=get("cityid","i");
			if($cityid){
				$cityids=M("district")->id_family($cityid);
				$where.=" AND cityid in("._implode($cityids).") ";
			}
			
			//筛选
			$qk_choice=get("qk_choice",'h');
			switch($qk_choice){
				case "grxz":
					//个人闲置
					$where.=" AND shoptype=0 ";
					break;
				case "baoyou":
					$where.=" AND baoyou=1 ";
					//包邮
					break;
				case "zuixin":
					//最新
					$order=" productid DESC";
					break;
			}
			$choice_day=get_post("choice_day","i");
			if($choice_day){
				$ctime=date("Y-m-d H:i:s",time()-3600*24*$choice_day);
				$where.=" AND createtime>'".$ctime."'";
			}
			$choice_min_price=get_post("choice_min_price","i");
			$choice_max_price=get_post("choice_max_price","i");
			if($choice_min_price<$choice_max_price){
				$where.=" AND (price>=".$choice_min_price." AND price<=".$choice_max_price." ) ";
			}
			 
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ershou_product")->select($option,$rscount);
			if(!empty($data)){
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
					"url"=>$url
				)
			);
			$tpl=MM("ershou","ershou_category")->getTpl($catid,1);
			$tpl=!empty($tpl)?$tpl:"ershou_product/list.html";
			$this->smarty->display($tpl);
		}
		
		public function onShow(){
			$productid=get_post("productid","i");
			$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
			$data["timeago"]=timeago(strtotime($data["createtime"]));
			$userid=$data["userid"];
			$author=M("user")->getUser($userid);
			$imgList=array();
			if(!empty($data['imgsdata'])){
				$imgs=explode(",",$data['imgsdata']);
				
				foreach($imgs as $img){
					$imgList[]=images_site($img);
				}
				 
			}
			$ssuserid=M("login")->userid;
			if($ssuserid){
				MM("ershou","ershou_history")->add(array(
					"userid"=>$ssuserid,
					"tablename"=>"ershou_product",
					"objectid"=>$productid
				));
			}
			
			MM("ershou","ershou_product")->changenum("view_num",1,"productid=".$productid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"author"=>$author,
				"imgList"=>$imgList
			));
			$this->smarty->display("ershou_product/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=ershou_product&a=my";
			$type=get('type',"h");
			switch($type){
				
				case "online":
					$where=" status=1 ";
					break;
				case "offline":
					$where=" status=2 ";
					break;
				default:
					$where=" status in(0,1,2) ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ershou_product")->select($option,$rscount);
			if(!empty($data)){
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
					"url"=>$url
				)
			);
			$this->smarty->display("ershou_product/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
				
			}
			$catList=MM("ershou","ershou_category")->select(array(
				"where"=>" pid=0 AND status=1 ",
				"order"=>" orderindex ASC"
			));
			$catList2=[];
			$catList3=[];
			$this->smarty->goassign(array(
				"data"=>$data,
				"catList"=>$catList,
				"catList2"=>$catList2,
				"catList3"=>$catList3
			));
			$this->smarty->display("ershou_product/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
			$productid=get_post("productid","i");
			$data=M("mod_ershou_product")->postData();
			$data["updatetime"]=date("Y-m-d H:i:s");
			//处理imgsdata
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
					$data["imgurl"]=$imgsdata[0];
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			}
			//解析cityid
			
			$data["status"]=1;
			$data["sitecheck"]=0;
			if($productid){
				$row=M("mod_ershou_product")->selectRow("productid=".$productid);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_ershou_product")->update($data,"productid=".$productid);
			}else{
				$data["shopid"]=$shop["shopid"];
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				$productid=M("mod_ershou_product")->insert($data);
				//订阅
				M("feeds")->add($userid,"mod_ershou_product",$productid);
				 
				
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$productid=get_post('productid',"i");
			$row=M("mod_ershou_product")->selectRow("productid=".$productid);
			if($row["userid"]!=M("login")->userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_ershou_product")->update(array("status"=>$status),"productid=".$productid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onPass(){
			M("login")->checkLogin();
			$productid=get_post('productid',"i");
			$row=M("mod_ershou_product")->selectRow("productid=".$productid);
			if($row["userid"]!=M("login")->userid){
				$this->goAll("暂无权限",1);
			}
			if($row["sitecheck"]==2){
				$this->goAll("审核不通过，无法上架",1);
			}
			M("mod_ershou_product")->update(array("status"=>1),"productid=".$productid);
			$this->goall("上架成功");
		}
		public function onForbid(){
			M("login")->checkLogin();
			$productid=get_post('productid',"i");
			$row=M("mod_ershou_product")->selectRow("productid=".$productid);
			if($row["userid"]!=M("login")->userid){
				$this->goAll("暂无权限",1);
			}

			M("mod_ershou_product")->update(array("status"=>2),"productid=".$productid);
			$this->goall("下架成功");
		}
		public function onDelete(){
			M("login")->checkLogin();
			$productid=get_post('productid',"i");
			$row=M("mod_ershou_product")->selectRow("productid=".$productid);
			if($row["userid"]!=M("login")->userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_ershou_product")->update(array("status"=>11),"productid=".$productid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>