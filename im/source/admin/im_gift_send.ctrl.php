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
			$url="/moduleadmin.php?m=im_gift_send&a=default";
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
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];
					$uids[]=$v["touserid"];
					$giftids[]=$v["giftid"];
				}
				$us=M("user")->getUserByIds(array_unique($uids));
				$gifts=MM("im","im_gift")->getListByIds($giftids,"giftid,title");
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["touser_nickname"]=$us[$v["touserid"]]["nickname"];
					$v["gift_title"]=$gifts[$v["giftid"]]["title"];
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
			$this->smarty->display("im_gift_send/index.html");
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