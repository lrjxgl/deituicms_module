<?php
class cj1Control extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" status=1 ";
		$url="/module.php?m=cj1";
		$limit=20;
		$start=get("per_page","i");
		$isfinish=get('isfinish','i');
		if($isfinish){
			$where.=" AND isfinish=1 ";
			$url.="&isfinish=1";
		}else{
			$where.=" AND isfinish=0 ";
		}
		$type=get("type","h");
		switch($type){
			case "doing":
				$where.=" AND isfinish=0 ";
				break;
			case "finish":
				$where.=" AND isfinish=1 ";
				break;
			
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("cj1","mod_cj1")->select($option,$rscount);
		 
		$this->smarty->goAssign(array(
			"data"=>$data,
			"type"=>$type
		));
		$this->smarty->display("cj1/index.html");
	}
	public function onZhaoshang(){
			$word="zhaoshang";
			$data=M("mod_cj1_html")->selectRow("word='".$word."'");
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("cj1/zhaoshang.html");
		}
		
	public function onShow(){
		$id=get('id','i');
		$data=M("mod_cj1")->selectRow("id=".$id);
		$data['less_num']=$data['need_num']-$data['join_num'];
		if($data['isfinish']){
			$winuser=M("user")->getUser($data['win_userid']);
			 
			$winlog=json_decode($data['winlog'],true);
		}
		$data['imgurl']=images_site($data['imgurl']);
		$userid=M("login")->userid;
		
		$fuserid=get('fuserid','i');
		if($userid){
			$myuser=M("user")->getUser($userid);
			$statlog=M("mod_cj1_statlog")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$statlog && !$data['isfinish'] && $data['status']==1 ){
				if($userid==$fuserid){
					$fuserid=0;
				}
				M("mod_cj1_statlog")->insert(array(
					"userid"=>$userid,
					"fuserid"=>$fuserid,
					"objectid"=>$id
				));
				if($fuserid && $userid!=$fuserid){
					//如果是邀请的则打入赏金
					//每项最多打赏3个
					$fnum=M("mod_cj1_statlog")->selectOne(array(
						"where"=>" fuserid=".$fuserid." AND objectid=".$id,
						"fields"=>" count(*) as ct "
					));
					if($fnum<3){
						MM("cj1","cj1_user")->addGold(array(
							"userid"=>$fuserid,
							"gold"=>1,
							"typeid"=>2,
							"content"=>$myuser['nickname']."点击了您的分享，获得了1个兑换币。"
						));
					}
				}
			}
			
		}
		 
		//参与用户列表
		$sql="select u.nickname,u.user_head from 
			".table('mod_cj1_order')." as o
			left join ".table('user')." as u 
			on o.userid=u.userid 
			where o.objectid=".$id." 
		";
		$orderlist=M("user")->getAll($sql);
		if($orderlist){
			foreach($orderlist as $k=>$v){
				$v['user_head']=images_site($v['user_head']);
				$orderlist[$k]=$v;
			}
		}
		//判断是否参加了
		$myorder=M("mod_cj1_order")->selectRow("userid=".$userid." AND objectid=".$id);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"winuser"=>$winuser,
			"winlog"=>$winlog,
			"orderlist"=>$orderlist,
			"myorder"=>$myorder
		));
		$this->smarty->display("cj1/show.html");
	}
	
	public function onBuy(){
		$id=get_post('id','i');
		$data=M("mod_cj1")->selectRow("id=".$id);
		if(empty($data)){
			$this->goALl("参数出错",1);
		}
		if($data['starttime']>time()){
			$this->goAll("活动还没开始哦",1);
		}
		$answer=get_post('answer','x');
		if($data['isask'] && $data['answer']!=$answer){
			$this->goAll("答案出错了".$answer,1);
		}
		$userid=M("login")->userid;
		$user=MM("cj1","cj1_user")->get($userid);
		if(!$user || $user['gold']<1){
			$this->goAll("兑换币不足哦",1);
		}
		$row=M("mod_cj1_order")->selectRow("userid=".$userid." AND objectid=".$id);
		if($data['isfinish']){
			$this->goALl("已经结束了",1);
		} 
		if(!$row ){
			MM("cj1","cj1_user")->addGold(array(
				"gold"=>-1,
				"typeid"=>1,
				"userid"=>$userid,
				"content"=>"你参与抢购活动发了1个兑换币，之前".$user['gold']."个,现在".($user['gold']-1)."个"
			));
		
			M("mod_cj1_order")->insert(array(
				"userid"=>$userid,
				"dateline"=>microtime(true),
				"objectid"=>$id
			));
			M("mod_cj1")->update(array(
				"join_num"=>$data['join_num']+1
			),"id=".$id);
			if($data['need_num']<=$data['join_num']+1){
				
			
				//选中获奖人
				$onum=M("mod_cj1_order")->selectOne(array(
					"where"=>" objectid=".$id,
					"fields"=>" sum(orderid) as ct"
				));
				$cnum=M("mod_cj1_order")->selectOne(array(
					"where"=>" objectid=".$id,
					"fields"=>" sum(dateline) as ct"
				));
				$allnum=M("mod_cj1_order")->selectOne(array(
					"where"=>" objectid=".$id,
					"fields"=>" count(*) as ct"
				));
				$a=explode(".",$cnum);
				$cnum=$a[1];
				$winkey=($onum+$cnum)%$allnum;
				$winorder=M("mod_cj1_order")->selectRow(array(
					"where"=>" objectid=".$id,
					"start"=>$winkey,
					"limit"=>1,
					"order"=>"orderid asc"
				));
				$winlog=array(
					"onum"=>$onum,
					"cnum"=>$cnum,
					"allnum"=>$allnum,
					"winkey"=>$winkey
				);
				M("mod_cj1")->update(array(
					"isfinish"=>1,
					"win_userid"=>$winorder['userid'],
					"winlog"=>json_encode($winlog)
				),"id=".$id);
				M("mod_cj1_order")->update(array(
					"iswin"=>1
				),"orderid=".$winorder['orderid']);
			}
			
		}else{
			$this->goAll("你已经参加了",1);
		}
		
		
		$this->goAll("购买成功");
	}
	
	public function onTest(){
		$id=1;
		$data=M("mod_cj1")->selectRow("id=".$id);
		 
		//选中获奖人
		$onum=M("mod_cj1_order")->selectOne(array(
			"where"=>" objectid=".$id,
			"fields"=>" sum(orderid) as ct"
		));
		$cnum=M("mod_cj1_order")->selectOne(array(
			"where"=>" objectid=".$id,
			"fields"=>" sum(dateline) as ct"
		));
		$allnum=M("mod_cj1_order")->selectOne(array(
			"where"=>" objectid=".$id,
			"fields"=>" count(*) as ct"
		));
		$a=explode(".",$cnum);
		$cnum=$a[1];
		$winkey=($onum+$cnum)%$allnum;
		$winorder=M("mod_cj1_order")->selectRow(array(
			"where"=>" objectid=".$id,
			"start"=>$winkey,
			"limit"=>1,
			"order"=>"orderid asc"
		));
		 
		$winlog=array(
			"onum"=>$onum,
			"cnum"=>$cnum,
			"allnum"=>$allnum,
			"winkey"=>$winkey
		);
		M("mod_cj1")->update(array(
			"isfinish"=>1,
			"win_userid"=>$winorder['userid'],
			"winlog"=>json_encode($winlog)
		),"id=".$id);
		M("mod_cj1_order")->update(array(
			"iswin"=>1
		),"orderid=".$winorder['orderid']);
		
		print_r($winorder);
		print_r($winlog);
	}
}

?>