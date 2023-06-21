<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_shop_commissionControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" 1 ";
			$url="moduleadmin.php?m=csc_shop_commission&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_shop_commission")->select($option,$rscount);
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
			$this->smarty->display("csc_shop_commission/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_csc_shop_commission")->selectRow(array("where"=>"shopid=".$shopid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("csc_shop_commission/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$stime=strtotime(post("stime"));
			$etime=strtotime(post("etime"));
			$data=M("mod_csc_shop_commission")->postData();
			$data["stime"]=$stime;
			$data["etime"]=$etime;
			$com=M("mod_csc_shop_commission")->selectRow("shopid=".$shopid);
			if($com){
				M("mod_csc_shop_commission")->update($data,"shopid='$shopid'");
			}else{
				M("mod_csc_shop_commission")->insert($data);
			}
			$this->goall("保存成功");
		}
	 
		
	}

?>