<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class youyao_findControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=youyao_find&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_find")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$map=M("open_map")->selectRow("map_com='gaode' AND map_app='web' ");
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"map"=>$map
				)
			);
			$this->smarty->display("youyao_find/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1)";
			$url="/module.php?m=youyao_find&a=default";
			$limit=20;
			$start=get("per_page","i");
			$gps=gps_get();
			$lat=$gps['lat'];
			$lng=$gps['lng'];
			$order=" id DESC";
			$fields="*";
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
				"where"=>$where,
				"fields"=>$fields
			);
			$rscount=true;
			$data=M("mod_youyao_find")->select($option,$rscount);
			if(!empty($data)){
				$uids=[];
				foreach($data as $k=>$v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["user"]=$us[$v["userid"]];
					$v["timeago"]=timeago(strtotime($v["createtime"]));
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
			$this->smarty->display("youyao_find/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_youyao_find")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("youyao_find/show.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=youyao_find&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_find")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$map=M("open_map")->selectRow("map_com='gaode' AND map_app='web' ");
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"map"=>$map
				)
			);
			$this->smarty->display("youyao_find/my.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_youyao_find")->selectRow(array("where"=>"id=".$id));
				
			}
			$map=M("open_map")->selectRow("map_com='gaode' AND map_app='web' ");
			$this->smarty->goassign(array(
				"data"=>$data,
				"map"=>$map
			));
			$this->smarty->display("youyao_find/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_youyao_find")->postData();
			if($id){
				M("mod_youyao_find")->update($data,"id=".$id);
			}else{
				$data["userid"]=$userid;
				M("mod_youyao_find")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_youyao_find")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_youyao_find")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_youyao_find")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>