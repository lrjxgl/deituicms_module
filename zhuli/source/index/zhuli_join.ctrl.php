<?php
class zhuli_joinControl extends skymvc{
	
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
		$data=M("mod_zhuli_join")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$ids[]=$v['zlid'];
			}
			$zls=MM("zhuli","zhuli")->getListByIds($ids);
			foreach($data as $k=>$v){
				$v['title']=$zls[$v['zlid']]['title'];
				$v['price']=$zls[$v['zlid']]['price'];
				$v['imgurl']=$zls[$v['zlid']]['imgurl'];
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		
		$this->smarty->display("zhuli_join/my.html");
	}
	public function onCreate(){
		$zlid=get_post('zlid','i');
		$userid=M("login")->userid;
		 
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$zlid);
		$zlmoney=$this->zlmoney($data['zl_min'],$data['zl_max']);
		$row=MM("zhuli","mod_zhuli_join")->selectRow(" zlid=".$zlid." AND userid=".$userid." ");
		if($row){
			$this->goAll("你已经领取了，快邀请朋友助力吧",123,$row,"/module.php?m=zhuli&a=join&id=".$row['id']);
		}
		$joinid=MM("zhuli","mod_zhuli_join")->insert(array(
			"zlid"=>$zlid,
			"userid"=>$userid,
			"dateline"=>time(),
			"zlnum"=>1,
			"zlmoney"=>$zlmoney
		));
		MM("zhuli","mod_zhuli_go")->insert(array(
			"zlid"=>$zlid,
			"userid"=>$userid,
			"dateline"=>time(),
			"joinid"=>$joinid,
			"zlmoney"=>$zlmoney
		));
		MM("zhuli","mod_zhuli")->update(array(
			"tj_user"=>$data['tj_user']+1,
			"tj_money"=>$data['tj_money']+$zlmoney,
			"tj_num"=>$data['tj_num']+1
		),"id=".$zlid);
		$url="/module.php?m=zhuli&id=".$joinid;
		$this->goAll("success",0,array("url"=>$url));
		
	}
	
	public function zlmoney($min=1,$max=100){
		$d=rand($min,$max);
		return intval($d);
	}
	
	
}
?>