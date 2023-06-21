<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shopmapControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 AND isrecommend=1 ";
			$url="/module.php?m=shopmap&a=default";
			$limit=24;
			$start=get("per_page","i");
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND (  title like '%".$keyword."%' or description like '%".$keyword."%' ) ";
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("shopmap","shopmap")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
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
			$this->smarty->display("shopmap/index.html");
		}
		public function onNear(){
			$where=" status=1  AND isrecommend=1 ";
			$url="/module.php?m=shopmap&a=default";
			$limit=24;
			$start=get("per_page","i");
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND (  title like '%".$keyword."%' or description like '%".$keyword."%' ) ";
			}
			$order=" id DESC ";
			$fields=" * ";
			$gps=gps_get();
			$lat=$gps['lat'];
			$lng=$gps['lng'];
			 
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
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"fields"=>$fields,
				"where"=>$where
			);
			$rscount=true;
			$data=MM("shopmap","shopmap")->Dselect($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
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
			$this->smarty->display("shopmap/near.html");
		}
		public function onSearch(){
			$where=" status=1 ";
			$url="/module.php?m=shopmap&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shopmap")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("shopmap/search.html");
		}
		
		public function onList(){
			$where=" status=1  AND isrecommend=1 ";
			$url="/module.php?m=shopmap&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shopmap")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("shopmap/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$userid=M("login")->userid;
			if($id){
				$data=M("mod_shopmap")->selectRow(array("where"=>"id={$id}"));
				if($data["status"]!=1 && $data["userid"]!=$userid){
					$this->goAll("该名片未开放",1);
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("shopmap/show.html");
		}
		
		public function onMaps(){
			
			$this->smarty->display("shopmap/maps.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=$userid AND status<3 ";
			$url="/module.php?m=shopmap&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shopmap")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("shopmap/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$userid=M("login")->userid;
			if($id){
				$data=M("mod_shopmap")->selectRow("id=".$id);
				if($data["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
			}
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("shopmap/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_shopmap")->postData();
			if($data["lat"]==0){
				$this->goAll("位置信息有误",1);
			}
			if(empty($data["title"])){
				$this->goAll("商家名称不能为空",1);
			}
			if(empty($data["address"])){
				$this->goAll("商家地址不能为空",1);
			}
			if(empty($data["imgurl"])){
				$this->goAll("商家门面照不能为空",1);
			}
			if(empty($data["description"])){
				//$this->goAll("商家简介不能为空",1);
			}
			
			$data["status"]=1;
			if($id){
				$row=M("mod_shopmap")->selectRow("id=".$id);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_shopmap")->update($data,"id=".$id);
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_shopmap")->insert($data);
			}
			
			
			$this->goAll("登记成功，请等待审核");
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_shopmap")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_shopmap")->update(array(
				"status"=>11
			),"id=".$id);
			$this->goAll("删除成功");
		}
		
	}

?>