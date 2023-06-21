<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ttcj_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=ttcj_order&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("ttcj","ttcj_order")->select($option,$rscount);
			$status_list=MM("ttcj","ttcj_order")->statusList();
			if($data){
				foreach($data as $v){
					$ids[]=$v['cjid'];
				}
				$cjs=MM("ttcj","ttcj")->getListByIds($ids);
				foreach($data as $k=>$v){
					$v['title']=$cjs[$v['cjid']]['title'];
					$v['imgurl']=$cjs[$v['cjid']]['imgurl'];
					$v['status_name']=$status_list[$v['status']];
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
			$this->smarty->display("ttcj_order/my.html");
		}
		
		
	}

?>