<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class im_gift_sendControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=im_gift_send&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" sendid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_im_gift_send")->select($option,$rscount);
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
			$this->smarty->display("im_gift_send/index.html");
		}
		
		public function onReceive(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" touserid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=im_gift_send&a=Receive";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" sendid DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_im_gift_send")->select($option,$rscount);
			if($list){
				foreach($list as $v){
					$uids[]=$v["userid"];
					$giftids[]=$v["giftid"];
				}
				$us=M("user")->getUserByIds($uids);
				$gifts=MM("im","im_gift")->getListByIds($giftids);
				foreach($list as $k=>$v){
					$v["user"]=$us[$v["userid"]];
					$v["gift"]=$gifts[$v["giftid"]];
					$list[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("im_gift_send/receive.html");
		}
		public function onStatus(){
			$sendid=get_post('sendid',"i");
			$status=get_post("status","i");
			M("mod_im_gift_send")->update(array("status"=>$status),"sendid=$sendid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$sendid=get_post('sendid',"i");
			M("mod_im_gift_send")->update(array("status"=>11),"sendid=$sendid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>