<?php
	class mscard_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			
			
			$url="/module.php?m=mscard&a=default";
			$cardid=get('cardid','i');
			if($cardid){
				$where="  cardid=".$cardid;
				$url.="&cardid=".$cardid;
			}else{
				$where=" userid=".$userid;
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
		 
		
	}
?>