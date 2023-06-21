<?php
	class mscard_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$where=" 1 ";
			
			$url="/moduleadmin.php?m=mscard&a=default";
			$cardid=get('cardid','i');
			if($cardid){
				$where .=" AND cardid=".$cardid;
				$url.="&cardid=".$cardid;
			} 
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mscard_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$cardids[]=$v['cardid'];
				}
				$cards=MM("mscard","mscard")->getListByIds($cardids);
				 
				foreach($data as $k=>$v){
					$v['nickname']=$cards[$v['cardid']]['nickname'];
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
			$this->smarty->display("mscard_order/index.html");
		}
		public function onPay(){
			$telephone=post('telephone','h');
			$money=post('money','h');
			$content=post('content','h');
			//
			$card=M("mod_mscard")->selectRow("shopid=".SHOPID." AND telephone='".$telephone."'");
		 
			if(!$card){
				$this->goAll("当前用户未办理会员卡",1);
			}
			if($card['money']<$money){
				$this->goAll("当前用户余额不足",1);
			}
			
			M("mod_mscard_order")->insert(array(
				"shopid"=>SHOPID,
				"userid"=>$card['userid'],
				 
				"money"=>$money,
				"content"=>$content,
				"dateline"=>time(),
				"grade"=>$money,
				"gold"=>$money,
				"cardid"=>$card['id'],
			));
			$rmoney=$card['money']-$money;
			M("mod_mscard")->update(array(
				"money"=>$rmoney
			),"id=".$card['id']);
			M("mod_mscard_log")->insert(array(
				"cardid"=>$card['id'],
				"content"=>"消费了{$money},原来{$card['money']}元，现在{$rmoney}元。",
				"dateline"=>time(),
				"shopid"=>SHOPID,
				"userid"=>$card['userid'],
				"money"=>$money
			));
			$this->goAll("消费成功");
		}
		
	}
?>