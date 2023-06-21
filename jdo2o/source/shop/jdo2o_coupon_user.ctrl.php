<?php
	/*Author:雷日锦*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class coupon_userControl extends skymvc{
		public $shopid;
		public $shop;
		public function __construct(){
			parent::__construct(); 
		}
		
		
		public function onDefault(){
			$start=get('per_page','i');
			$limit=20;
			$where=" 1=1 ";
			$url=APPSHOP."?m=coupon&a=user";
			$option=array(
				"where"=>$where,
				"start"=>$start,
				"limit"=>$limit
			);
			$rscount=true;
			$data=M("coupon_user")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$uids[]=$v['userid'];
					$cids[]=$v['coupon_id'];
				}
				$uids && $us=M("user")->getUserByIds($uids);
				$cids && $cos=M("coupon")->getByIds($cids);
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=$us[$v['userid']]['user_head'];
					$v['title']=$cos[$v['coupon_id']]['title'];
					$v['end_time']=$cos[$v['coupon_id']]['end_time'];
					$v['money']=$cos[$v['coupon_id']]['money'];
					$v['type_id']=$cos[$v['coupon_id']]['type_id'];
					if(time()-$v['notice_time']>3600*48){
						$v['notice']=1;
					}else{
						$v['notice']=0;
					}
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
	 		$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(array(
				"data"=>$data,
				"rscount"=>$rscount,
				"pagelist"=>$pagelist,
				"per_page"=>$per_page
			));
			$this->smarty->display("coupon_user/index.html");
		}
		
		public function onNotice(){
			$id=get_post('id',"i");
			$row=M("coupon_user")->selectRow("id=".$id);
			if($row['shopid']!=SHOPID){
				$this->goALL("暂无权限",1);
			}
			//两天内不能通知
			if(time()-$row['notice_time']<3600*2){
				$this->goALL("你已经通知过了",1);
			}
			M("notice")->add(array(
				"userid"=>$row['userid'],
				"coupon"=>'你有优惠券快要过期了，赶紧<a href="/index.php/m=coupon&a=my">去消费吧</a>'
			));
			M("coupon_user")->update(array("notice_time"=>time()),"id=".$id);
			$this->goALL("通知成功",0);
		}
		
	}

?>