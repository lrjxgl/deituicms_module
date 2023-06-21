<?php
class zhuli_goControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$rscount=true;
		$start=get('per_page','i');
		$limit=24;
		$where=" userid=".$userid;
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$data=M("mod_zhuli_go")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$ids[]=$v['zlid'];
			}
			$zls=MM("zhuli","zhuli")->getListByIds($ids);
			foreach($data as $k=>$v){
				$v['title']=$zls[$v['zlid']]['title'];
				$v['price']=$zls[$v['zlid']]['price'];
				$v['imgurl']=images_site($zls[$v['zlid']]['imgurl']);
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		
		$this->smarty->display("zhuli_go/my.html");
	}
	
	public function onGo(){
		$joinid=get('joinid');
		$userid=M("login")->userid;	 
		
		$join=MM("zhuli","mod_zhuli_join")->selectRow("id=".$joinid);
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$join['zlid']);
		$zlmoney=$this->zlmoney($data['zl_min'],$data['zl_max']);
		$allmoney=$join['zlmoney']+$zlmoney;
		$row=MM("zhuli","mod_zhuli_go")->selectRow(" joinid=".$joinid." AND userid=".$userid." ");
		if($row){
			$this->goAll("你已经助力了",1);
		}
		MM("zhuli","mod_zhuli_go")->insert(array(
			"zlid"=>$join['zlid'],
			"userid"=>$userid,
			"dateline"=>time(),
			"joinid"=>$joinid,
			"zlmoney"=>$zlmoney
		));
	
		MM("zhuli","mod_zhuli_join")->update(array("zlnum"=>$join['zlnum']+1,"zlmoney"=>$allmoney),"id=".$joinid);
		
		MM("zhuli","mod_zhuli")->update(array(
			"total_num"=>$data['total_num']+1
		),"id=".$join['zlid']);
		$this->goALl("助力成功",0);
	}
	
	public function zlmoney($min=1,$max=100){
		$d=rand($min,$max);
		return intval($d);
	}
	
}
?>