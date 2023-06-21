<?php
class zhuli_shaidanControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$type=get("type","h");
		$url="/module.php?m=zhuli_shaidan";
		$rscount=true;
		$start=get('per_page','i');
		$limit=24;
		$where=" status=1 ";
		
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$data=M("mod_zhuli_shaidan")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$v['imgsdata'] && $v['imgsdata']=explode(",",$v['imgsdata']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"pagelist"=>$pagelist
			
		));
		$this->smarty->display("zhuli_shaidan/index.html");
	}
	
	public function onZhuli(){
		$id=get_post('id','i');
	 
		$zhuli=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		$url="/module.php?m=zhuli_shaidan";
		$rscount=true;
		$start=get('per_page','i');
		$limit=24;
		$where=" status=1 AND zlid=".$id;
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$data=M("mod_zhuli_shaidan")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$v['imgsdata'] && $v['imgsdata']=explode(",",$v['imgsdata']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"pagelist"=>$pagelist
		));
		$this->smarty->assign(array(
			 
			"user"=>$user,
	 
			"zhuli"=>$zhuli,
		));
		$this->smarty->display("zhuli_shaidan/zhuli.html");
	}
	
	public function onShow(){
		
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$url="/module.php?m=zhuli_shaidan&a=my";
		$rscount=true;
		$start=get('per_page','i');
		$limit=24;
		$where=" status=1 AND userid=".$userid;
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$data=M("mod_zhuli_shaidan")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$v['imgsdata'] && $v['imgsdata']=explode(",",$v['imgsdata']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("zhuli_shaidan/my.html");
	}
	
	public function onAdd(){
		$userid=M("login")->userid;
		$orderid=get_post('orderid','id');
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['userid']!=$userid){
			$this->goAll("暂无权限");
		}
		$data=M("mod_zhuli_shaidan")->selectRow("orderid=".$orderid);
		
		$imgsdata=array();
		if($data){
			$imgsdata=explode(",",$data['imgsdata']);
		}
		$this->smarty->assign(array(
			"order"=>$order,
			"data"=>$data,
			"imgsdata"=>$imgsdata
		));
		$this->smarty->display("zhuli_shaidan/add.html");
	}
	public function onSave(){
		$userid=M("login")->userid;
		$orderid=get_post('orderid','id');
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['userid']!=$userid){
			$this->goAll("暂无权限");
		}
		$data=M("mod_zhuli_shaidan")->postData();
		$row=M("mod_zhuli_shaidan")->selectRow("orderid=".$orderid);
		$data['zlid']=$order['zlid'];
		$data['ordermoney']=$order['money'];
		$data['userid']=$userid;
		if($row){
			M("mod_zhuli_shaidan")->update($data,"orderid=".$orderid);
		}else{
			M("mod_zhuli_shaidan")->insert($data);
		}
		
		 
		
		$this->goAll("保存成功");
	}
}
?>