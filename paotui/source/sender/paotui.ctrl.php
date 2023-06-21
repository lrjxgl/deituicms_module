<?php
class paotuiControl extends skymvc{
	
	public function onDefault(){
		 
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		if(!$sender["isauth"]){
			$this->goall("请先认证",11,0,"/sender.php?m=paotui_sender&a=auth");
		}
		$this->smarty->display("index.html");
	}
	
	public function onList(){
		$pconfig=MM("paotui","paotui_config")->get();
		$sender=MM("paotui","paotui_sender")->get(SENDERID);
		$status_list=MM("paotui","paotui")->status_list();
		$typelist=MM("paotui","paotui")->typelist();
		$typeid=get("typeid","i"); 
		$where=" status=0 AND ispay=1 ";
		if($typeid){
			$where.="AND typeid=".$typeid;
		}
		if($pconfig["lazy_time"]>0 && $sender["isvip"]!=1){
			$time=date("Y-m-d H:i:s",time()-$pconfig["lazy_time"]);
			$where.=" AND updatetime<'".$time."'";
		}
		$cityid=get("cityid","i");
		if($cityid && $pconfig["morecity"]==1){
			$where.=" AND cityid=".$cityid;
		}
		$list=M("mod_paotui")->select(array(
			"where"=>$where,
			"order"=>"createtime ASC",
			"limit"=>24
		));
		$catlist=MM("paotui","paotui_category")->catlist();
		$newTime=time();
		if($list){
			
			foreach($list as $k=>$v){
				if($v["status"]==0 && $v["ispay"]==0){
					$v["status_name"]="待支付";
				}else{
					$v['status_name']=$status_list[$v['status']];
				}
				if($v["catid"]){
					$v["catid_title"]=$catlist[$v["catid"]]["title"];
				}else{
					$v["catid_title"]="";
				}
				$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
				$v["typeid_name"]=$typelist[$v["typeid"]]["title"];
				$v["fromaddr"]=json_decode($v["fromaddr"]);
				$v["toaddr"]=json_decode($v["toaddr"]);
				$list[$k]=$v;
			}
		}
	 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"typelist"=>$typelist,
			"typeid"=>$typeid,
			"time"=>$newTime,
			"pconfig"=>$pconfig
			
		));
		 
	
	}
	
	public function onOrder(){
		$id=get("id","i");
		$paotui=M("mod_paotui")->selectRow("id=".$id);
		if($paotui["status"]!=0 || $paotui["ispay"]!=1){
			$this->goAll("该单已经被抢了",1);
		}
		M("mod_paotui")->begin();
		
		$orderid=M("mod_paotui_order")->insert(array(
			"ptid"=>$id,
			"userid"=>$paotui["userid"],
			"senderid"=>SENDERID,
			"createtime"=>date("Y-m-d H:i:s"),
			"updatetime"=>date("Y-m-d H:i:s"),
			"money"=>$paotui["money"]
		));
		M("mod_paotui")->update(array(
			"senderid"=>SENDERID,
			"status"=>1
		),"id=".$id);
		//发送给用户消息
		M("notice")->add([
			"title"=>"跑腿任务提示",
			"content"=>"您的跑腿任务，有人接单了",
			"userid"=>$paotui["userid"],
			"linkurl"=>[
				"path"=>"/module.php",
				"m"=>"paotui",
				"a"=>"show",
				"param"=>"id=".$id
			]
			
		]);
		M("mod_paotui")->commit();
		$this->goAll("抢单成功");
	}
	
	public function onNew(){
		$time=get("time","i");
		$where=" status=0 AND ispay=1 AND createtime>'".date("Y-m-d H:i:s",$time)."' ";
		$nume=M("mod_paotui")->selectOne(array(
			"where"=>$where,
			"fields"=>"count(*)"
		));
		if(!$num){
			$num=0;
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>$num
		));
	}
	
	
}
?>