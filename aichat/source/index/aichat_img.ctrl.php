<?php
class aichat_imgControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$this->smarty->display("aichat_img/index.html");
	}
	public function onQueueNum(){
		$userid=M("login")->userid;
		$num=M("mod_aichat_img_task")->selectOne(array(
			"where"=>" create_status=0 ",
			"fields"=>"count(*)"
		));
		$maxid=M("mod_aichat_img_task")->selectOne(array(
			"where"=>" create_status=0 AND userid=".$userid,
			"fields"=>"id",
			"order"=>" id ASC"
		));
		$mynum=M("mod_aichat_img_task")->selectOne(array(
			"where"=>" create_status=0 AND id<=".intval($maxid),
			"fields"=>"count(*)"
		));
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"num"=>$num,
				"mynum"=>intval($mynum)
			)
			
		));
	}
	public function onList(){
		$where=" status=1 ";
		$order=" id DESC";
		$type=get("type","h");
		
		switch($type){
			case "recommend":
				$where.=" AND isrecommend=1 ";
				
				break;
			case "hot":
				$where.=" AND ishot=1 ";
				$order=" love_num DESC ";
				break;
			 
			default:
				
				break;
		}
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"where"=>$where
		);
		$rscount=true;
		$list=M("mod_aichat_img")->select($option,$rscount); 
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page; 
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list,
				"per_page"=>$per_page,
				"rscount"=>$rscount,
			)
			
		));
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$where=" create_status=1 AND status in(0,1,2) AND userid=".$userid;
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$list=M("mod_aichat_img")->select($option,$rscount); 
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page; 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
		));
		$this->smarty->display("aichat_img/my.html");
	}
	public function onCreate(){
		M("login")->checkLogin();
		$cfg=MM("aichat","aichat_config")->get();
		$img_token=$cfg["img_pay_token"];
		$this->smarty->goAssign(array(
			"img_token"=>$img_token
		));
		$this->smarty->display("aichat_img/create.html");
	}
	
	public function onGet(){
		$id=get("id","i");
		$data=M("mod_aichat_img_task")->selectRow("id=".$id);
		$data["imgurl"]=images_site($data["imgurl"]);
		$this->goAll("success",0,$data);
	}
	
	public function onCreateSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$prompt=post("prompt","x");
		if(empty($prompt)){
			$this->goAll("提示词不能为空",1);
		}
		//查看未完成的
		$ct=M("mod_aichat_img_task")->selectOne(array(
			"where"=>" userid=".$userid." AND create_status=0 ",
			"fields"=>" count(*)"
		));
		if($ct>10 && $userid!=4){
			$this->goAll("你已经有5张图片正在处理,请稍后再添加..",1);
		}
		//处理金币
		$cfg=MM("aichat","aichat_config")->get();
		$token=$cfg["img_pay_token"];
		$aiuser=MM("aichat","aichat_user")->get($userid);
		if($aiuser["token"]<$token && $aiuser["vip_etime"]<time()){
			$this->goAll("可消费Token数量不足",1);
		}
		//查看prompt是否已经有了
		$pro=M("mod_aichat_img_prompt")->selectRow("prompt='".$prompt."'");
		if(!empty($pro)){
			$prompt_en=$pro["prompt_en"];
			M("mod_aichat_img_prompt")->update(array(
				"create_num"=>$pro["create_num"]+1
			),"promptid=".$pro["promptid"]);
			$promptid=$pro["promptid"];
		}else{
			//翻译成英文
			require ROOT_PATH."/module/aichat/xffanyi.php";
			$xfConfig=M("open_xunfei")->get();
			$fy = new xffanyi($xfConfig);
			$fs=$fy->xfyun($prompt);
			if($fs["error"]==0){
				$prompt_en=$fs["content"];
			}else{
				$prompt_en=$prompt;
			}
			$promptid=M("mod_aichat_img_prompt")->insert(array(
				"prompt"=>$prompt,
				"prompt_en"=>$prompt_en,
				"userid"=>$userid,
				"create_num"=>1,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		
		M("user")->begin();
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$userid,
			"num"=>-$token,
			"content"=>"生成图片消耗".$token."个token"
		));
		$id=M("mod_aichat_img_task")->insert(array(
			"prompt"=>$prompt,
			"prompt_en"=>$prompt_en,
			"userid"=>$userid,
			"promptid"=>$promptid,
			"createtime"=>date("Y-m-d H:i:s")
		));
		$data=M("mod_aichat_img_task")->selectRow("id=".$id);
		//插入到队列
		$finishdata=array(
			"tablename"=>"mod_aichat_img",
			"id"=>$id,
			"action"=>"create_img"
		);
		$queuekey="aichat_create_img";
		$queue=new queue($queuekey);
		$queue->lpush(array(
			"action"=>"create_img",
			"prompt"=>$prompt,
			"prompt_en"=>$prompt_en,
			"finishdata"=>arr2str($finishdata)
		));
		M("user")->commit();
		$this->goAll("正在创作图片中..",0,$data);
	}
	
	public function onDelete(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$id=get("id","i");
		M("mod_aichat_img")->update(array(
			"status"=>11
		),"id=".$id);
		$this->goAll("删除成功");
	}
	
	public function onClick(){
		$id=get("id","i");
		M("mod_aichat_img")->changenum("view_num",1,"id=".$id);
		$this->goAll("success");
	}
	
	public function onReQueue(){
		$row=M("mod_aichat_img_task")->selectRow(array(
			"where"=>" create_status=1 ",
			"order"=>" id DESC"
		));
		$time=strtotime($row["createtime"])-300;
		$ctime=date("Y-m-d H:i:s",$time);
		$res=M("mod_aichat_img_task")->select(array(
			"where"=>" create_status=0 ",
			"order"=>" id DESC"
		));
		 
		if(!empty($res)){
			foreach($res as $rs){
				//插入到队列
				$finishdata=array(
					"tablename"=>"mod_aichat_img",
					"id"=>$rs["id"],
					"action"=>"create_img"
				);
				$queuekey="aichat_create_img";
				$queue=new queue($queuekey);
				$queue->lpush(array(
					"action"=>"create_img",
					"prompt"=>$rs["prompt"],
					"prompt_en"=>$rs["prompt_en"],
					"finishdata"=>arr2str($finishdata)
				));
			}
		}
		echo "success";
	}
	
}