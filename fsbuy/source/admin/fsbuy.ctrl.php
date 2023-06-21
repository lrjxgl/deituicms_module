<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsbuyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4) ";
			$url="/moduleadmin.php?m=fsbuy&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" fsid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsbuy")->select($option,$rscount);
			$statusList=MM("fsbuy","fsbuy")->statusList();
			if($data){
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v['imgurl']);
					$v["status_name"]=$statusList[$v["status"]];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("fsbuy/index.html");
		}
		
		public function onAdd(){
			$fsid=get_post("fsid","i");
			if($fsid){
				$data=M("mod_fsbuy")->selectRow(array("where"=>"fsid={$fsid}"));
				
			}
			$statusList=MM("fsbuy","fsbuy")->statusList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"statusList"=>$statusList
			));
			$this->smarty->display("fsbuy/add.html");
		}
		
		public function onSave(){
			
			$fsid=get_post("fsid","i");

			$data=M("mod_fsbuy")->postData();
			if($fsid){
				M("mod_fsbuy")->update($data,"fsid='$fsid'");
			}else{
				M("mod_fsbuy")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$fsid=get_post('fsid',"i");
			$status=get_post("status","i");
			M("mod_fsbuy")->update(array("status"=>$status),"fsid=$fsid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$fsid=get_post('fsid',"i");
			M("mod_fsbuy")->update(array("status"=>11),"fsid=$fsid");
			$this->goAll("删除成功");
			 
		}
		
		public function onCopy(){
			$fsid=get("fsid","i");
			$data=M("mod_fsbuy")->selectRow("fsid=".$fsid);
			unset($data["fsid"]);
			$data["status"]=0;
			$data["buynum"]=0;
			$data["title"].="_copy";
			$ksList=M("mod_fsbuy_ks")->select(array(
				"where"=>" fsid=".$fsid
			));
			$fsid=M("mod_fsbuy")->insert($data);
			if($ksList){
				foreach($ksList as $ks){
					$ks["fsid"]=$fsid;
					unset($ks["ksid"]);
					M("mod_fsbuy_ks")->insert($ks);
				}
			}
			$this->goAll("复制成功");
		}
		
		public function onpinsuccess(){
			$fsid=get("fsid","i");
			$data=M("mod_fsbuy")->selectRow("fsid=".$fsid);
			M("mod_fsbuy_order")->update(array(
				"pin_success"=>1
			)," ispay=1 AND fsid=".$fsid);
			$this->goAll("处理成功");
		}
		
		public function onBack(){
			$fsid=get("fsid","i");
			$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
			if($fsbuy["status"]!=3){
				$this->goAll("还未结束,无法处理",1);
			}
			if($fsbuy["fstype"]!=2){
				$this->goAll("不是阶梯团购，无返利",1);
			}
			$step_config=MM("fsbuy","fsbuy")->parseStepConfig($fsbuy["step_config"],$fsbuy["buynum"]);
			$discount=MM("fsbuy","fsbuy")->getStepConfigDiscount($step_config);			 
			$list=M("mod_fsbuy_order")->select(array(
				"where"=>" status=3 AND fstype=2 AND ispay=1 AND isback=0  AND fsid=".$fsid
			));
			 
			if(!empty($list)){
				M("user")->begin();
				foreach($list as $order){
					$discount_money=$order["money"]*(100-$discount)/100;
					M("mod_fsbuy_order")->update(array(
						"isback"=>1
					),"orderid=".$order["orderid"]);
					M("user")->addMOney(array(
						"userid"=>$order["userid"],
						"money"=>$discount_money,
						"content"=>"参与团购返利".$discount_money."元"
					));
					M("mod_fsbuy_backorder")->insert(array(
						"userid"=>$order["userid"],
						"money"=>$discount_money,
						"content"=>"参与团购".$fsbuy["title"].",参与".$fsbuy["buynum"]."人，返利".$discount_money."元",
						"orderid"=>$order["orderid"],
						"fsid"=>$order["fsid"],
						"createtime"=>date("Y-m-d H:i:s")
					));
				}
				M("user")->commit();
			}
			$this->goAll("处理成功");
			
		}
		
	}

?>