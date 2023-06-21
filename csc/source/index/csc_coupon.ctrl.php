<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_couponControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=csc_coupon&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_coupon")->select($option,$rscount);
			if($data){
				$shopids=array();
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("csc","csc_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["shopname"]; 
					$v['end_day']=date("Y-m-d",strtotime($v['end_time']));
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
			$this->smarty->display("csc_coupon/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=csc_coupon&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_coupon")->select($option,$rscount);
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
			$this->smarty->display("csc_coupon/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_csc_coupon")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("csc_coupon/show.html");
		}
		
		public function onGetCoupon(){
			M("login")->checklogin(1);
			$id=get('id','i');
			$coupon=M("mod_csc_coupon")->selectRow("id=".$id);
			if($coupon['status']!=1){
				$this->goAll("优惠券不可用",1);
				
			}
			if($coupon['amount']<=$coupon['get_num']){
				$this->goAll("优惠券领完了",1);
			}
			if(strtotime($coupon['end_time'])<time()){
				$this->goAll("促销时间结束了",1);
			}
			$ct=M("mod_csc_coupon_user")->SelectOne(array(
				"where"=>" coupon_id=".$id." AND userid=".M("login")->userid." ",
				"fields"=>" count(*) as ct"
			));
			if($ct>=$coupon['limit_num']){
				$this->goAll("你已经领取过了",1);
			}
			M("mod_csc_coupon_user")->insert(array(
				"coupon_id"=>$id,
				"userid"=>M("login")->userid,
				"dateline"=>time(),
				"siteid"=>$coupon['siteid'],
				"shopid"=>$coupon['shopid']
			));
			//更新优惠券领取人数
			M("mod_csc_coupon")->update(array(
				"get_num"=>$coupon['get_num']+1
			),"id=".$id);
			$this->goAll("领取成功");
		}
		
	}

?>