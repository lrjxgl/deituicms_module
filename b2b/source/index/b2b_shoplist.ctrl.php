<?php
class b2b_shoplistControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status=1 ";
		$catid=get("catid","i");
		if($catid){
			$where.=" AND catid=".$catid;
			$cat=MM("b2b","b2b_shop_category")->selectRow("catid=".$catid);
		}
		
		$order=" shopid DESC ";
		$fields=" * ";
		$gps=gps_get();
		$lat=$gps['lat'];
		$lng=$gps['lng'];
		$type=get("type","h");
		 switch($type){
			 case "near":
				if($lat && $lng){
					$fields.=",".' ROUND(  
						6378.138 * 2 * ASIN(  
							SQRT(  
								POW(  
									SIN(  
										(  
											'.$lat.' * PI() / 180 - lat * PI() / 180  
										) / 2  
									),  
									2  
								) + COS('.$lat.' * PI() / 180) * COS(lat * PI() / 180) * POW(  
									SIN(  
										(  
											'.$lng.' * PI() / 180 - lng * PI() / 180  
										) / 2  
									),  
									2  
								)  
							)  
						)  
					) AS distance_num  ';
					$order=" distance_num ASC";
					
				} 
				break;
		 }
		$orderList=MM("b2b","b2b_shop")->orderList();
		$filterList=array(
			"send_free"=>"免费配送",
			"send_all"=>"零元起送",
			"cspf"=>"超时赔付",
			"newshop"=>"新店"
		);
		$sc_id=get("sc_id","i");
		if($sc_id){		 
			$scids=M("site_city")->id_family($sc_id);
			$where.=" AND sc_id in("._implode($scids).")";
			$url.="&sc_id=".$sc_id;
		}
		//多选择
		$choice=get_post("choice","h");
		if(!empty($choice)){
			$ces=explode(",",$choice);
			foreach($ces as $k=>$v){
				if(empty($v)){
					unset($ces[$k]);
				}else{
					$ces[$k]=sql(html($v));
				}
			}
			if(!empty($ces)){
				foreach($ces as $filter){
					switch($filter){
						case "send_free":
								$where.=" AND express_money=0 ";
							break;
						case "send_all":
								$where.=" AND send_startprice=0 ";
							break;
						case "newshop":
								$where.=" AND isnew=1 ";
							break;
						case 'cspf':
								$where.=" AND cspf=1 ";
							break;
						
							break;
						case 'isnew':
								$where.=" AND isnew=1 ";
							break;
					}
				}
			}
		}
		//价格筛选
		$sprice=get("price","h");
		if($sprice){
			if(strpos($sprice,"以上")>0){
				$where.=" AND avg_price>=".intval($sprice);
			}elseif(strpos($sprice,"以下")>0){
				$where.=" AND avg_price<=".intval($sprice);
			}else{
				$ex=explode("-",$sprice);
				$minPrice=intval($ex[0]);
				$maxPrice=intval($ex[1]);
				$where.=" AND avg_price>=".$minPrice." AND avg_price<".$maxPrice;
			}
		}
		//优惠
		$discount=get("discount","h");
		switch($discount){
			case "new":
				$where.=" AND discount_new=1";
				break;
			case "discount":
				$where.=" AND discount_goods=1";
				break;
			case "manjian":
				$where.=" AND discount_coupon=1";
				break;
		}
		$ob=get('orderby','h');
		switch($ob){
			case 'distance':
					$orderby='distance_num DESC';
				break;
			case 'sold_num':
					$orderby='sold_num DESC';
				break;
			case 'raty_grade':
					$orderby='raty_grade DESC';
				break;
			case 'express_money':
					$orderby="express_money ASC";
				break;
			case "send_startprice":
					$orderby="send_startprice ASC";
				break;
			case "avg_price":
					$orderby="avg_price DESC";
				break;
			default:
				$orderby='grade DESC';
			 	break;
		}
		$start=get("per_page","i");
		$limit=24; 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"fields"=>$fields,
			"where"=>$where
		);
		$rscount=true;
		$shopList=MM("b2b","b2b_shop")->DselectWindow($option,$rscount);
		$catList=MM("b2b","b2b_shop_category")->children(0);
		$site_city=M("site_city")->children(0);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$orderList=MM("b2b","b2b_shop")->orderList();
		$priceList=array(
			"20以下",
			"20-40元",
			"40元以上"
		);
		$this->smarty->goAssign(array(
			"shopList"=>$shopList,
			"per_page"=>$per_page,
			"pagelist"=>$pagelist,
			"cat"=>$cat,
			"catid"=>$catid,
			"catList"=>$catList,
			"orderList"=>$orderList,
			"site_city"=>$site_city,
			"filterList"=>$filterList,
			"orderList"=>$orderList,
			"priceList"=>$priceList
		));
		$this->smarty->display("b2b_shoplist/index.html");
	}
	
	public function onFilter(){
		$orderList=MM("b2b","b2b_shop")->orderList();
		$priceList=array(
			"20以下",
			"20-40元",
			"40元以上"
		);
		$filterList=array(
			"send_free"=>"免费配送",
			"send_all"=>"零元起送",
			//"cspf"=>"超时赔付",
			"newshop"=>"新店"
		);
		$discList=array(
			"manjian"=>"满减优惠",
			"discount"=>"单品折扣",
			"new"=>"新人立减"
		);
		$this->goAll("success",0,array(
			"filterList"=>$filterList,
			"orderList"=>$orderList,
			"priceList"=>$priceList,
			"discList"=>$discList
		));
	}
	
	public function onMap(){
		$this->smarty->goAssign(array(
			"e"=>1
		));
		$this->smarty->display("b2b_shoplist/map.html");
	}
}