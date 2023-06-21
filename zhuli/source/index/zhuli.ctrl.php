<?php
class zhuliControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		
	}
	public function onInit(){
		M("login")->checklogin();
	}
	
	 
	public function onDefault(){
		$id=get_post('id','i');
	 	if(!$id){
	 		header("Location:/module.php?m=zhuli_list");
	 		exit;
	 	}
	 
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		$data['content']=M("mod_zhuli_data")->selectOne(array(
			"where"=>"id=".$id,
			"fields"=>"content"
		));
		$userid=M("login")->userid;
		$myjoin=MM("zhuli","mod_zhuli_join")->selectRow("zlid=".$id." AND userid=".$userid);
		$user=M("user")->selectRow("userid=".$userid);
		$golist=MM("zhuli","mod_zhuli_go")->select($option);
		if($golist){
			foreach($golist as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($golist as $k=>$v){
				$u=$us[$v['userid']];
				$v['user']=$u;
				$v['zlmoney']=$data['zlmoney']*$v['zlmoney']*0.01;
				$golist[$k]=$v;
			}
			
		}
		$this->smarty->assign(array(
			 
			"user"=>$user,
			"data"=>$data,
			"golist"=>$golist,
			"myjoin"=>$myjoin
		));
		$this->smarty->display("zhuli/index.html");
	}
	
	public function onJoin(){
		$id=get_post('id','i'); 
		
		
		$join=MM("zhuli","mod_zhuli_join")->selectRow("id=".$id);
		if(!$join){
			$url="/module.php?m=zhuli_list";
			$this->goAll("数据出错",1,0,$url);
		}
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$join['zlid']);
		$join['kl_money']=$data['zlmoney']*$join['zlmoney']*0.01;
		$userid=$join['userid'];	 
		$user=M("user")->selectRow("userid=".$userid);
		$option=array(
			"where"=>" zlid=".$join['zlid']." AND joinid=".$join['id']."",
			"order"=>" id DESC"
		);
		
		$golist=MM("zhuli","mod_zhuli_go")->select($option);
		if($golist){
			foreach($golist as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($golist as $k=>$v){
				$u=$us[$v['userid']];
				$v['user']=$u;
				$v['zlmoney']=$data['zlmoney']*$v['zlmoney']*0.01;
				$golist[$k]=$v;
			}
			
		}
		 
		$muserid=M("login")->userid;
		$myjoin=MM("zhuli","mod_zhuli_join")->selectRow("zlid=".$join['zlid']." AND userid=".$muserid);
		$isme=0;
		if($join['userid']==$muserid){
			$isme=1;
		}
		$buy_money=$data['market_price']-$join['zlmoney']*$data['zlmoney']*0.01;
		$this->smarty->assign(array(
			 "isme"=>$isme,
			"user"=>$user,
			"data"=>$data,
			"zhuli"=>$data,
			"buy_money"=>$buy_money,
			"join"=>$join,
			"golist"=>$golist,
			"myjoin"=>$myjoin
		));
		$this->smarty->display("zhuli/join.html");
	}
	
	public function onDesc(){
		$id=get_post('id','i');
	 
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		$d=MM("zhuli","mod_zhuli_data")->selectRow("id=".$id);
		$data['content']=$d['content'];
		 
		$this->smarty->assign(array(
			 
			 
			"data"=>$data,
			"zhuli"=>$data,
		));
		$this->smarty->display("zhuli/desc.html");
	}
	
	public function onPaihang(){
		$id=get_post('id','i');
	 
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		$option=array(
			"where"=>"zlid=".$id,
			"order"=>"zlmoney DESC,id DESC",
			"limit"=>$data['max_win']
		);
		$phlist=MM("zhuli","mod_zhuli_join")->select($option);
		if($phlist){
			foreach($phlist as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($phlist as $k=>$v){
				$u=$us[$v['userid']];
				$u["telephone"]=substr($u["telephone"],0,4)."***".substr($u["telephone"],7);
				$v['user']=$u;
				$phlist[$k]=$v;
			}
		}
		$this->smarty->assign(array(
			 
			"user"=>$user,
			"data"=>$data,
			"zhuli"=>$data,
			"phlist"=>$phlist,
		));
		$this->smarty->display("zhuli/paihang.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$id=get_post('id','i');
	 
		$data=MM("zhuli","mod_zhuli")->selectRow("id=".$id);
		 
		$userid=M("login")->userid;
		$status_list=array(
			0=>"未确认",
			1=>"已确认",
			2=>"已发货",
			3=>"已完成",
			8=>"已取消"
		);  
		$myjoin=MM("zhuli","mod_zhuli_join")->selectRow("zlid=".$id." AND userid=".$userid);
		if($myjoin){
			$myjoin['zlmoney']=$myjoin['zlmoney']*$data['zlmoney']*0.01;
		}
		$myorder=MM("zhuli","mod_zhuli_order")->selectRow("zlid=".$id." AND userid=".$userid);
		if($myorder){
			$myorder['status_name']=$status_list[$myorder['status']];
		}
		
		$option=array(
			"where"=>" zlid=".$id." AND userid=".$userid,
			"order"=>" id DESC"
		);
		
		$golist=MM("zhuli","mod_zhuli_go")->select($option);
		if($golist){
			foreach($golist as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($golist as $k=>$v){
				$u=$us[$v['userid']];
				$v['user']=$u;
				$v['zlmoney']=$data['zlmoney']*$v['zlmoney']*0.01;
				$golist[$k]=$v;
			}
			
		}
		$this->smarty->assign(array(
			"golist"=>$golist,
			"myjoin"=>$myjoin,
			"myorder"=>$myorder, 
			"zhuli"=>$data,
		));
		$this->smarty->display("zhuli/my.html");
	}
	
	public function onList(){
		
		
		$this->smarty->display("zhuli/index.html");
	}
	
	 
	
	
	
	
	
	
	
	
}

?>