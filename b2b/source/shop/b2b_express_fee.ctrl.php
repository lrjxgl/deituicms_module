<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class b2b_express_feeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID;
		 
			$url="/moduleshop.php?m=b2b_express_fee&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_b2b_express_fee")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v['shopid'];
				}
				$shops=MM("b2b","b2b_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v['shop_name']=$shops[$v['shopid']]['shopname'];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
	 	 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("b2b_express_fee/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_b2b_express_fee")->selectRow(array("where"=>"id={$id}"));
				
			}
			$provincelist=M("district")->select(array("where"=>"upid=0"));
			if($id){
				$opt=array(
					"where"=>"pid=".$id
				);
				$cityids=MM("b2b","b2b_express_fee_city")->getCityIds($opt);
				//选出其他配送方案的省份
				$oids=MM("b2b","b2b_express_fee_city")->getCityIds(array(
					"where"=>" shopid=".SHOPID." AND pid!=".$id
				));
				 
			}else{
				//选出其他配送方案的省份
				$oids=MM("b2b","b2b_express_fee_city")->getCityIds(array(
					"where"=>" shopid=".SHOPID
				));
			}
			if($oids){
				foreach($provincelist as $k=>$v){
					if(in_array($provincelist[$k]['id'],$oids)){
						unset($provincelist[$k]);
					}
				}
			}
			if($provincelist && $cityids){
				foreach($provincelist as $k=>$v){
					if(in_array($v['id'],$cityids)){
						$v['selected']=1;
					}
					$provincelist[$k]=$v; 
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"provincelist"=>$provincelist
			));
			$this->smarty->display("b2b_express_fee/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_b2b_express_fee")->postData();
			 
			$data['shopid']=SHOPID;
			if($id){
				$row=M("mod_b2b_express_fee")->selectRow("id=".$id);
				if($row['shopid']!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_b2b_express_fee")->update($data,"id='$id'");
			}else{
				$id=M("mod_b2b_express_fee")->insert($data);
			}
			$cityids=$_POST['cityids'];
			 
			$delids=$hvids=MM("b2b","b2b_express_fee_city")->selectCols(array(
				"where"=>" shopid=".SHOPID." AND  pid=".$id,
				"fields"=>"areaid"
			));
			if($cityids){
				foreach($cityids as $c){
					if($c){
						$cids[]=intval($c);
					}
				}
				//删除
				 
				$delids=array_diff($hvids,$cids);
				//添加
				$addids=array_diff($cids,$hvids);							
			}
			
			if($addids){
					foreach($addids as $areaid){
						if(!MM("b2b","b2b_express_fee_city")->selectRow("pid=".$id." AND shopid=".SHOPID." AND areaid=".$areaid)){
							MM("b2b","b2b_express_fee_city")->insert(array(
								"pid"=>$id,
								"areaid"=>$areaid,
								"shopid"=>SHOPID
							));
						}
					}
			}
		 
			if($delids){
				foreach($delids as $areaid){
					MM("b2b","b2b_express_fee_city")->delete("pid=".$id." AND shopid=".SHOPID." AND areaid=".$areaid);
				}
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_b2b_express_fee")->selectRow("id=".$id);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_b2b_express_fee")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_b2b_express_fee")->selectRow("id=".$id);
			if($row['shopid']!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_b2b_express_fee")->delete("id=".$id);
			MM("b2b","b2b_express_fee_city")->delete("pid=".$id);
			$this->goall("删除成功",0);
		}
		
		
	}

?>